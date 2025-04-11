<?php
// src/Application/Command/ImportCsvCommand.php
namespace App\Application\Command;

class ImportCsvCommand
{
    private int $fileId;

    public function __construct(int $fileId)
    {
        $this->fileId = $fileId;
    }

    public function getFileId(): int
    {
        return $this->fileId;
    }
}
