<?php

namespace App\Service;

use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Filesystem\Filesystem;

class CSVUploadService
{
    private string $csvUploadDir;
    private Filesystem $filesystem;

    public function __construct(string $csvUploadDir)
    {
        $this->csvUploadDir = $csvUploadDir;
        $this->filesystem = new Filesystem();
    }

    public function upload(UploadedFile $file): string
    {
        if ($file->getClientOriginalExtension() !== 'csv') {
            throw new \Exception('Invalid file type. Only CSV files are allowed.');
        }

        $filename = uniqid() . '.' . $file->getClientOriginalExtension();
        $filePath = $this->csvUploadDir . '/' . $filename;

        $file->move($this->csvUploadDir, $filename);

        return $filePath;
    }
}