<?php

namespace App\Domain\Entity;

class CsvFile
{
    private string $id;
    private string $filename;
    private \DateTimeImmutable $uploadedAt;
    private string $status;

    public function __construct(string $filename)
    {
        $this->id = uniqid();
        $this->filename = $filename;
        $this->uploadedAt = new \DateTimeImmutable();
        $this->status = 'pending';
    }

    // Getters and setters
    public function getId(): string
    {
        return $this->id;
    }

    public function getFilename(): string
    {
        return $this->filename;
    }

    public function getUploadedAt(): \DateTimeImmutable
    {
        return $this->uploadedAt;
    }

    public function getStatus(): string
    {
        return $this->status;
    }

    public function setStatus(string $status): void
    {
        $this->status = $status;
    }
}
