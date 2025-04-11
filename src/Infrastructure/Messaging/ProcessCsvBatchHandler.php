<?php

namespace App\Infrastructure\Messaging;

use App\Domain\Repository\CsvBatchRepository;
use App\Application\Message\ProcessCsvBatchJob;

class ProcessCsvBatchHandler
{
    private CsvBatchRepository $repository;
    
    public function __construct(CsvBatchRepository $repository)
    {
        $this->repository = $repository;
    }
    
    public function __invoke(ProcessCsvBatchJob $message)
    {
        $batch = $this->repository->findById($message->getBatchId());
        
        if (!$batch) {
            return;
        }
        
        // Process the batch
        // ...
        
        // Update the batch status
        $batch->setStatus('processed');
        $this->repository->save($batch);
    }
}