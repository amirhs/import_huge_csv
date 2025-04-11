<?php
// src/Domain/Repository/CsvFileRepository.php
namespace App\Domain\Repository;

use App\Domain\Entity\CsvFile;

interface CsvFileRepository
{
    public function save(CsvFile $csvFile): void;
    public function findById(string $id): ?CsvFile;
    public function findByFilename(string $filename): ?CsvFile;
}
