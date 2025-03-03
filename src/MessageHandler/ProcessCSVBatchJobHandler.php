<?php

namespace App\MessageHandler;

use App\Message\ProcessCsvBatchJob;
use Doctrine\DBAL\Connection;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;
use Psr\Log\LoggerInterface;

#[AsMessageHandler]
class ProcessCSVBatchJobHandler
{
    private Connection $connection;
    private LoggerInterface $logger;

    public function __construct(Connection $connection, LoggerInterface $logger)
    {
        $this->connection = $connection;
        $this->logger = $logger;
    }

    public function __invoke(ProcessCsvBatchJob $job)
    {
        $batch = $job->getBatch();

        if (empty($batch)) {
            $this->logger->warning('Empty batch received.');
            return;
        }

        $values = [];
        $params = [];

        foreach ($batch as $row) {
            if (!isset($row['Emp ID'], $row['E Mail'])) {
                $this->logger->warning('Row missing name or email', $row);
                continue;
            }
            $values[] = '(?, ?)';
            $params[] = $row['Emp ID'];
            $params[] = $row['E Mail'];
        }

        if (empty($values)) {
            $this->logger->warning('No valid rows to insert.');
            return;
        }

        $sql = 'INSERT INTO employee (employee_id, email) VALUES ' . implode(', ', $values);

        $affected = $this->connection->executeStatement($sql, $params);
        unset($batch, $values, $params);
    }
}