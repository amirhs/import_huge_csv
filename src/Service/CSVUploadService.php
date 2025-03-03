<?php

namespace App\Service;

use App\Entity\CsvFile;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Filesystem\Filesystem;

class CSVUploadService
{
    private string $csvUploadDir;
    private Filesystem $filesystem;
    private EntityManagerInterface $entityManager;

    public function __construct(string $csvUploadDir, EntityManagerInterface $entityManager)
    {
        $this->csvUploadDir = $csvUploadDir;
        $this->filesystem = new Filesystem();
        $this->entityManager = $entityManager;
    }

    public function upload(UploadedFile $file): string
    {
        if ($file->getClientOriginalExtension() !== 'csv') {
            throw new \Exception('Invalid file type. Only CSV files are allowed.');
        }

        $filename = uniqid() . '.' . $file->getClientOriginalExtension();
        $filePath = $this->csvUploadDir . '/' . $filename;

        $file->move($this->csvUploadDir, $filename);

        $csvFile = new CsvFile($filename);
        $this->entityManager->persist($csvFile);
        $this->entityManager->flush();

        return $filePath;
    }
}