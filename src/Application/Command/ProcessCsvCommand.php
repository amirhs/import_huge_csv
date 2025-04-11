<?php
// src/Application/Command/ProcessCsvCommand.php
namespace App\Application\Command;

class ProcessCsvCommand
{
    private string $fileId;

    public function __construct(string $fileId)
    {
        $this->fileId = $fileId;
    }

    public function getFileId(): string
    {
        return $this->fileId;
    }
}
