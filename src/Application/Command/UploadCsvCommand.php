<?php
// src/Application/Command/UploadCsvCommand.php
namespace App\Application\Command;

use Symfony\Component\HttpFoundation\File\UploadedFile;

class UploadCsvCommand
{
    private UploadedFile $file;

    public function __construct(UploadedFile $file)
    {
        $this->file = $file;
    }

    public function getFile(): UploadedFile
    {
        return $this->file;
    }
}
