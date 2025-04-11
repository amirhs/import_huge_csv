<?php
// src/Infrastructure/Persistence/DoctrineCsvFileRepository.php
namespace App\Infrastructure\Persistence;

use App\Domain\Entity\CsvFile;
use App\Domain\Repository\CsvFileRepository;
use Doctrine\ORM\EntityManagerInterface;

class DoctrineCsvFileRepository implements CsvFileRepository
{
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function save(CsvFile $csvFile): void
    {
        $this->entityManager->persist($csvFile);
        $this->entityManager->flush();
    }

    public function findById(string $id): ?CsvFile
    {
        return $this->entityManager->getRepository(CsvFile::class)->find($id);
    }

    public function findByFilename(string $filename): ?CsvFile
    {
        return $this->entityManager->getRepository(CsvFile::class)
            ->findOneBy(['filename' => $filename]);
    }
}
