<?php
// src/Application/Command/DeleteEmployeeCommand.php
namespace App\Application\Command;

class DeleteEmployeeCommand
{
    private string $employeeId;

    public function __construct(string $employeeId)
    {
        $this->employeeId = $employeeId;
    }

    public function getEmployeeId(): string
    {
        return $this->employeeId;
    }
}
