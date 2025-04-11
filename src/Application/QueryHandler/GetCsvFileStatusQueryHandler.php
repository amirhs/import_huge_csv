<?php
// src/Application/QueryHandler/GetCsvFileStatusQueryHandler.php
namespace App\Application\QueryHandler;

use App\Application\DTO\CsvFileStatusDTO;
use App\Application\Query\GetCsvFileStatusQuery;
use App\Domain\Repository\CsvFileRepository;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;

class GetCsvFileStatusQueryHandler implements MessageHandlerInterface
{
    private CsvFileRepository $csvFileRepository;

    public function __construct(CsvFileRepository $csvFileRepository)
    {
        $this->csvFileRepository = $csvFileRepository;
    }

    public function __invoke(GetCsvFileStatusQuery $query): CsvFileStatusDTO
    {
        $csvFile = $this->csvFileRepository->findById($query->getFileId());
        
        if (!$csvFile) {
            throw new \Exception('CSV file not found');
        }
        
        return new CsvFileStatusDTO(
            $csvFile->getId(),
            $csvFile->getFilename(),
            $csvFile->getStatus(),
            $csvFile->getUploadedAt()
        );
    }
}
