<?php

namespace App\Entity;

use App\Repository\CsvFileRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CsvFileRepository::class)]
class CsvFile
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: "string", unique: true)]
    private string $fileName;

    #[ORM\Column(type: "string", length: 20)]
    private string $status = 'uploaded'; // Possible values: uploaded, importing, imported

    #[ORM\Column(type: "datetime")]
    private \DateTime $createdAt;

    public function __construct(string $fileName)
    {
        $this->fileName = $fileName;
        $this->status = 'uploaded';
        $this->createdAt = new \DateTime();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFileName(): string
    {
        return $this->fileName;
    }

    public function getStatus(): string
    {
        return $this->status;
    }

    public function setStatus(string $status): self
    {
        $this->status = $status;
        return $this;
    }

    public function getCreatedAt(): \DateTime
    {
        return $this->createdAt;
    }
}
