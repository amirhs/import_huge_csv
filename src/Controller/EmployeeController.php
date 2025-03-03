<?php

namespace App\Controller;

use App\Service\CSVUploadService;
use App\Service\EmployeeService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class EmployeeController extends AbstractController
{
    private CSVUploadService $uploadService;
    private EmployeeService $employeeService;

    public function __construct(CSVUploadService $uploadService, EmployeeService $employeeService)
    {
        $this->uploadService = $uploadService;
        $this->employeeService = $employeeService;
    }

    #[Route('/api/employee', methods: ['POST'])]
    public function upload(Request $request): JsonResponse
    {
        $file = $request->files->get('file');

        if (!$file) {
            return new JsonResponse(['error' => 'No file uploaded'], 400);
        }

        try {
            $this->uploadService->upload($file);
            return new JsonResponse(['uploadFileSuccessfully' => true]);
        } catch (\Exception $e) {
            return new JsonResponse(['error' => $e->getMessage()], 400);
        }
    }

    #[Route('/api/employee/{employeeId}', methods: ['GET'])]
    public function getEmployee(string $employeeId): JsonResponse
    {
        $employee = $this->employeeService->getEmployee($employeeId);

        if (!$employee) {
            return $this->json([
                'error' => "Employee with EmployeeID $employeeId does not exist."
            ], JsonResponse::HTTP_NOT_FOUND);
        }

        return $this->json([
            'id' => $employee->getId(),
            'name' => $employee->getEmployeeId(),
            'email' => $employee->getEmail(),
        ]);
    }

    #[Route('/api/employee/{employeeId}', name: 'delete_employee', methods: ['DELETE'])]
    public function deleteEmployee(string $employeeId): JsonResponse
    {
        $employeeDeleted = $this->employeeService->deleteEmployee($employeeId);

        if (!$employeeDeleted) {
            return $this->json([
                'error' => "Employee with EmployeeID $employeeId does not exist."
            ], JsonResponse::HTTP_NOT_FOUND);
        }

        return $this->json([
            'message' => "Employee with EmployeeID $employeeId has been deleted."
        ]);
    }
}
