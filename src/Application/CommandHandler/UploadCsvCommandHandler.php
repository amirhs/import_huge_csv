<?php
// src/Application/CommandHandler/UploadCsvCommandHandler.php
namespace App\Application\CommandHandler;

use App\Application\Command\UploadCsvCommand;
use App\Domain\Entity\CsvFile;
use App\Domain\Repository\CsvFileRepository;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
class UploadCsvCommandHandler
{
    private string $csvUploadDir;
    private CsvFileRepository $csvFileRepository;

    public function __construct(
        string $csvUploadDir,
        CsvFileRepository $csvFileRepository
    ) {
        $this->csvUploadDir = $csvUploadDir;
        $this->csvFileRepository = $csvFileRepository;
    }

    public function __invoke(UploadCsvCommand $command): int
    {
        $file = $command->getFile();
        
        if ($file->getClientOriginalExtension() !== 'csv') {
            throw new \Exception('Invalid file type. Only CSV files are allowed.');
        }

        $filename = uniqid() . '.' . $file->getClientOriginalExtension();
        $filePath = $this->csvUploadDir . '/' . $filename;

        $file->move($this->csvUploadDir, $filename);

        $csvFile = new CsvFile($filename);
        $this->csvFileRepository->save($csvFile);

        return $csvFile->getId();
    }
}
