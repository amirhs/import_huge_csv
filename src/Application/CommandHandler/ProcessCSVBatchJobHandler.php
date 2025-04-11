<?php

namespace App\Application\CommandHandler;

use App\Application\Bus\CommandHandlerInterface;
use App\Application\Command\ProcessCsvBatchCommand;
use App\Domain\Repository\EmployeeRepository;
use App\Domain\Entity\Employee;
use Psr\Log\LoggerInterface;

class ProcessCsvBatchCommandHandler implements CommandHandlerInterface
{
    private EmployeeRepository $employeeRepository;
    private LoggerInterface $logger;

    public function __construct(
        EmployeeRepository $employeeRepository, 
        LoggerInterface $logger
    ) {
        $this->employeeRepository = $employeeRepository;
        $this->logger = $logger;
    }

    public function __invoke(ProcessCsvBatchCommand $command)
    {
        $batch = $command->getBatch();

        if (empty($batch)) {
            $this->logger->warning('Empty batch received.');
            return;
        }

        $processedCount = 0;

        foreach ($batch as $row) {
            if (!isset($row['Emp ID'], $row['E Mail'])) {
                $this->logger->warning('Row missing employee ID or email', ['row' => $row]);
                continue;
            }

            try {
                $employeeId = $row['Emp ID'];
                $email = $row['E Mail'];

                // Check if employee already exists
                $employee = $this->employeeRepository->findByEmployeeId($employeeId);

                if ($employee) {
                    // Update existing employee
                    $employee->setEmail($email);
                } else {
                    // Create new employee
                    $employee = new Employee($employeeId, $email);
                }

                $this->employeeRepository->save($employee);
                $processedCount++;
            } catch (\Exception $e) {
                $this->logger->error('Error processing employee row', [
                    'row' => $row,
                    'error' => $e->getMessage()
                ]);
            }
        }

        $this->logger->info("Processed {$processedCount} employees from batch");
    }
}