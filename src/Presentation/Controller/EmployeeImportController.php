<?php

namespace App\Presentation\Controller;

use App\Application\Bus\CommandBusInterface;
use App\Application\Command\ImportCsvCommand;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

class EmployeeImportController extends AbstractController
{
    private CommandBusInterface $commandBus;

    public function __construct(CommandBusInterface $commandBus)
    {
        $this->commandBus = $commandBus;
    }

    #[Route('/api/employee/import/{fileId}', methods: ['POST'])]
    public function import(int $fileId): JsonResponse
    {
        try {
            $this->commandBus->dispatch(new ImportCsvCommand($fileId));
            return new JsonResponse(['message' => 'Import started']);
        } catch (\Exception $e) {
            return new JsonResponse(['error' => $e->getMessage()], 400);
        }
    }
}