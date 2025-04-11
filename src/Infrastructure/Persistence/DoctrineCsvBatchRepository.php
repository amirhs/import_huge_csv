<?php
// src/Infrastructure/Persistence/DoctrineCsvBatchRepository.php
namespace App\Infrastructure\Persistence;

use App\Domain\Entity\CsvBatch;
use App\Domain\Repository\CsvBatchRepository;
use Doctrine\ORM\EntityManagerInterface;

class DoctrineCsvBatchRepository implements CsvBatchRepository
{
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function save(CsvBatch $batch): void
    {
        $this->entityManager->persist($batch);
        $this->entityManager->flush();
    }

    public function findById(string $id): ?CsvBatch
    {
        return $this->entityManager->getRepository(CsvBatch::class)->find($id);
    }

    public function findPendingBatches(int $limit): array
    {
        return $this->entityManager->getRepository(CsvBatch::class)
            ->createQueryBuilder('b')
            ->where('b.status = :status')
            ->setParameter('status', 'pending')
            ->setMaxResults($limit)
            ->getQuery()
            ->getResult();
    }
}
