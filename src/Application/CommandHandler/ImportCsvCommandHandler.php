<?php
// src/Application/CommandHandler/ImportCsvCommandHandler.php
namespace App\Application\CommandHandler;

use App\Application\Bus\CommandHandlerInterface;
use App\Application\Command\ImportCsvCommand;
use App\Domain\Repository\CsvFileRepository;
use App\Domain\Service\CsvImportService;

class ImportCsvCommandHandler implements CommandHandlerInterface
{
    private CsvFileRepository $csvFileRepository;
    private CsvImportService $csvImportService;

    public function __construct(
        CsvFileRepository $csvFileRepository,
        CsvImportService $csvImportService
    ) {
        $this->csvFileRepository = $csvFileRepository;
        $this->csvImportService = $csvImportService;
    }

    public function __invoke(ImportCsvCommand $command): void
    {
        $csvFile = $this->csvFileRepository->findById($command->getFileId());
        
        if (!$csvFile) {
            throw new \Exception("CSV file with ID {$command->getFileId()} not found.");
        }
        
        $this->csvImportService->processFile($csvFile);
    }
}
