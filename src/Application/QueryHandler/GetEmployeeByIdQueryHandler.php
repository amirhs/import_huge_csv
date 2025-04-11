<?php
// src/Application/QueryHandler/GetEmployeeByIdQueryHandler.php
namespace App\Application\QueryHandler;

use App\Application\Bus\QueryHandlerInterface;
use App\Application\DTO\EmployeeDTO;
use App\Application\Query\GetEmployeeByIdQuery;
use App\Domain\Repository\EmployeeRepository;

class GetEmployeeByIdQueryHandler implements QueryHandlerInterface
{
    private EmployeeRepository $employeeRepository;

    public function __construct(EmployeeRepository $employeeRepository)
    {
        $this->employeeRepository = $employeeRepository;
    }

    public function __invoke(GetEmployeeByIdQuery $query): EmployeeDTO
    {
        $employee = $this->employeeRepository->findByEmployeeId($query->getEmployeeId());
        
        if (!$employee) {
            throw new \Exception("Employee with EmployeeID {$query->getEmployeeId()} does not exist.");
        }
        
        return new EmployeeDTO(
            $employee->getId(),
            $employee->getEmployeeId(),
            $employee->getEmail()
        );
    }
}
