<?php

namespace App\MessageHandler;

use App\Entity\Employee;
use App\Message\ProcessCsvBatchJob;
use App\Repository\EmployeeRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;
use Psr\Log\LoggerInterface;

#[AsMessageHandler]
class ProcessCSVBatchJobHandler
{
    private EntityManagerInterface $entityManager;
    private EmployeeRepository $employeeRepository;
    private LoggerInterface $logger;

    public function __construct(
        EntityManagerInterface $entityManager,
        EmployeeRepository $employeeRepository,
        LoggerInterface $logger
    ) {
        $this->entityManager = $entityManager;
        $this->employeeRepository = $employeeRepository;
        $this->logger = $logger;
    }

    public function __invoke(ProcessCsvBatchJob $job)
    {
        $batch = $job->getBatch();

        if (empty($batch)) {
            $this->logger->warning('Empty batch received.');
            return;
        }

        $batchSize = 0;
        foreach ($batch as $row) {
            if (!isset($row['Emp ID'], $row['E Mail'])) {
                $this->logger->warning('Row missing name or email', $row);
                continue;
            }
            
            $employee = new Employee();
            $employee->setEmployeeId($row['Emp ID']);
            $employee->setEmail($row['E Mail']);
            
            $this->entityManager->persist($employee);
            $batchSize++;
            
            // Flush every 20 entities to avoid memory issues with large batches
            if ($batchSize % 20 === 0) {
                $this->entityManager->flush();
                $this->entityManager->clear();
            }
        }

        if ($batchSize === 0) {
            $this->logger->warning('No valid rows to insert.');
            return;
        }

        // Final flush for any remaining entities
        $this->entityManager->flush();
        $this->logger->info('Successfully imported ' . $batchSize . ' employees');
    }
}