<?php
// src/Application/Query/GetEmployeeByIdQuery.php
namespace App\Application\Query;

class GetEmployeeByIdQuery
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
