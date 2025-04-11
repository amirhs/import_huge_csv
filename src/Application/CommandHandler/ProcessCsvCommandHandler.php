<?php
// src/Application/CommandHandler/ProcessCsvCommandHandler.php
namespace App\Application\CommandHandler;

use App\Application\Command\ProcessCsvCommand;
use App\Domain\Repository\CsvFileRepository;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;

class ProcessCsvCommandHandler implements MessageHandlerInterface
{
    private CsvFileRepository $csvFileRepository;
    
    public function __construct(CsvFileRepository $csvFileRepository)
    {
        $this->csvFileRepository = $csvFileRepository;
    }
    
    public function __invoke(ProcessCsvCommand $command): void
    {
        $csvFile = $this->csvFileRepository->findById($command->getFileId());
        
        if (!$csvFile) {
            throw new \Exception('CSV file not found');
        }
        
        // Process the CSV file
        // ...
        
        // Update status
        $csvFile->setStatus('processed');
        $this->csvFileRepository->save($csvFile);
    }
}
