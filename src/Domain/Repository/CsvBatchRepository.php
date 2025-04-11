<?php

namespace App\Domain\Repository;

use App\Domain\Entity\CsvBatch;

interface CsvBatchRepository
{
    public function save(CsvBatch $batch): void;
    public function findById(string $id): ?CsvBatch;
    public function findPendingBatches(int $limit): array;
}