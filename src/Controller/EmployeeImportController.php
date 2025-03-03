<?php

namespace App\Controller;

use App\Service\CsvImportService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class EmployeeImportController extends AbstractController
{
    private CsvImportService $csvImportService;

    public function __construct(CsvImportService $csvImportService)
    {
        $this->csvImportService = $csvImportService;
    }

    #[Route('/api/employee/import/{fileId}', methods: ['POST'])]
    public function import(int $fileId): JsonResponse
    {
        try {
            $this->csvImportService->import($fileId);
            return new JsonResponse(['message' => 'Import started']);
        } catch (\Exception $e) {
            return new JsonResponse(['error' => $e->getMessage()], 400);
        }
    }
}