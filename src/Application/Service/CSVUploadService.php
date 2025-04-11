<?php

namespace App\Application\Service;

use App\Domain\Entity\CsvFile;
use App\Domain\Repository\CsvFileRepository;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Filesystem\Filesystem;

class CSVUploadService
{
    private string $csvUploadDir;
    private Filesystem $filesystem;
    private CsvFileRepository $csvFileRepository;

    public function __construct(
        string $csvUploadDir, 
        CsvFileRepository $csvFileRepository
    ) {
        $this->csvUploadDir = $csvUploadDir;
        $this->filesystem = new Filesystem();
        $this->csvFileRepository = $csvFileRepository;
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
        $this->csvFileRepository->save($csvFile);

        return $filePath;
    }
}