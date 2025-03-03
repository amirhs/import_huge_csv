<?php

namespace App\Service;

use App\Entity\Employee;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class EmployeeService
{
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function getEmployee(int $employeeId): ?Employee
    {
        $employee = $this->entityManager->getRepository(Employee::class)
            ->findOneBy(['employeeId' => $employeeId]);

        if (!$employee instanceof \App\Entity\Employee) {
            return null;
        }

        return $employee;
    }

    public function deleteEmployee(string $employeeId): bool
    {
        $employee = $this->getEmployee($employeeId);

        if (null === $employee) {
            return false;
        }

        $this->entityManager->remove($employee);
        $this->entityManager->flush();
        $this->entityManager->clear();

        return true;
    }
}
