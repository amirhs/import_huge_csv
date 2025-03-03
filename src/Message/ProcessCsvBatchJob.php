<?php

namespace App\Message;

class ProcessCsvBatchJob
{
    private array $batch;
    private int $fileId;

    public function __construct(array $batch, int $fileId)
    {
        $this->batch = $batch;
        $this->fileId = $fileId;
    }

    public function getBatch(): array
    {
        return $this->batch;
    }

    public function getFileId(): int
    {
        return $this->fileId;
    }
}