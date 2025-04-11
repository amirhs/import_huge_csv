<?php

namespace App\Service;

use App\Entity\CsvFile;
use App\Infrastructure\Messaging\ProcessCsvBatchJob;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\Messenger\MessageBusInterface;

class CsvImportService
{
    private EntityManagerInterface $entityManager;
    private MessageBusInterface $bus;
    private string $uploadDir;
    private LoggerInterface $logger;

    public function __construct(EntityManagerInterface $entityManager, MessageBusInterface $bus, string $csvUploadDir, LoggerInterface $logger)
    {
        $this->entityManager = $entityManager;
        $this->bus = $bus;
        $this->uploadDir = $csvUploadDir;
        $this->logger = $logger;
    }

    public function import(int $fileId): void
    {
        $csvFile = $this->entityManager->getRepository(CsvFile::class)->find($fileId);

        if (!$csvFile) {
            throw new \Exception("CSV file not found.");
        }

        $csvFile->setStatus('importing');
        $this->entityManager->flush();

        $filePath = $this->uploadDir . '/' . $csvFile->getFileName();
        $file = fopen($filePath, 'r');

        if (!$file) {
            throw new \Exception("Failed to open file: " . $filePath);
        }

        $header = fgetcsv($file);
        $batch = [];
        $batchSize = 1000;

        while (($row = fgetcsv($file)) !== false) {
            $data = array_combine($header, $row);

            if ($this->validateRow($data)) {
                $batch[] = $data;
            } else {
                $this->logger->error('Invalid row founded', ['row' => $data]);
            }

            if (count($batch) >= $batchSize) {
                $this->bus->dispatch(new ProcessCsvBatchJob($batch, $csvFile->getId()));
                $batch = [];
            }
        }

        if (!empty($batch)) {
            $this->bus->dispatch(new ProcessCsvBatchJob($batch, $csvFile->getId()));
        }

        fclose($file);

        $csvFile->setStatus('imported');

        $this->entityManager->flush();
    }

    private function validateRow(array $row): bool
    {
        return !empty($row['Emp ID']) && filter_var($row['E Mail'], FILTER_VALIDATE_EMAIL);
    }
}