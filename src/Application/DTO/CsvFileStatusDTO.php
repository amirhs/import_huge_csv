<?php
// src/Application/DTO/CsvFileStatusDTO.php
namespace App\Application\DTO;

class CsvFileStatusDTO
{
    private string $id;
    private string $filename;
    private string $status;
    private \DateTimeImmutable $uploadedAt;

    public function __construct(
        string $id,
        string $filename,
        string $status,
        \DateTimeImmutable $uploadedAt
    ) {
        $this->id = $id;
        $this->filename = $filename;
        $this->status = $status;
        $this->uploadedAt = $uploadedAt;
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getFilename(): string
    {
        return $this->filename;
    }

    public function getStatus(): string
    {
        return $this->status;
    }

    public function getUploadedAt(): \DateTimeImmutable
    {
        return $this->uploadedAt;
    }
}
