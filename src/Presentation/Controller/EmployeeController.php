<?php

namespace App\Presentation\Controller;

use App\Application\Bus\CommandBusInterface;
use App\Application\Bus\QueryBusInterface;
use App\Application\Command\UploadCsvCommand;
use App\Application\Command\DeleteEmployeeCommand;
use App\Application\Query\GetEmployeeByIdQuery;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class EmployeeController extends AbstractController
{
    private CommandBusInterface $commandBus;
    private QueryBusInterface $queryBus;

    public function __construct(
        CommandBusInterface $commandBus,
        QueryBusInterface $queryBus
    ) {
        $this->commandBus = $commandBus;
        $this->queryBus = $queryBus;
    }

    #[Route('/api/employee', methods: ['POST'])]
    public function upload(Request $request): JsonResponse
    {
        $file = $request->files->get('file');

        if (!$file) {
            return new JsonResponse(['error' => 'No file uploaded'], 400);
        }

        try {
            $fileId = $this->commandBus->dispatch(new UploadCsvCommand($file));
            return new JsonResponse(['uploadFileSuccessfully' => true, 'fileId' => $fileId]);
        } catch (\Exception $e) {
            return new JsonResponse(['error' => $e->getMessage()], 400);
        }
    }

    #[Route('/api/employee/{employeeId}', methods: ['GET'])]
    public function getEmployee(string $employeeId): JsonResponse
    {
        try {
            $employee = $this->queryBus->handle(new GetEmployeeByIdQuery($employeeId));
            
            return $this->json([
                'id' => $employee->getId(),
                'employee_id' => $employee->getEmployeeId(),
                'email' => $employee->getEmail(),
            ]);
        } catch (\Exception $e) {
            return $this->json([
                'error' => $e->getMessage()
            ], JsonResponse::HTTP_NOT_FOUND);
        }
    }

    #[Route('/api/employee/{employeeId}', name: 'delete_employee', methods: ['DELETE'])]
    public function deleteEmployee(string $employeeId): JsonResponse
    {
        try {
            $this->commandBus->dispatch(new DeleteEmployeeCommand($employeeId));
            
            return $this->json([
                'message' => "Employee with EmployeeID $employeeId has been deleted."
            ]);
        } catch (\Exception $e) {
            return $this->json([
                'error' => $e->getMessage()
            ], JsonResponse::HTTP_NOT_FOUND);
        }
    }
}
