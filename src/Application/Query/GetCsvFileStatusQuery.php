<?php
// src/Application/Query/GetCsvFileStatusQuery.php
namespace App\Application\Query;

class GetCsvFileStatusQuery
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
