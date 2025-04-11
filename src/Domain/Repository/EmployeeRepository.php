<?php
// src/Domain/Repository/EmployeeRepository.php
namespace App\Domain\Repository;

use App\Domain\Entity\Employee;

interface EmployeeRepository
{
    public function save(Employee $employee): void;
    public function remove(Employee $employee): void;
    public function findById(int $id): ?Employee;
    public function findByEmployeeId(string $employeeId): ?Employee;
}
