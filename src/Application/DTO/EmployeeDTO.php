<?php
// src/Application/DTO/EmployeeDTO.php
namespace App\Application\DTO;

class EmployeeDTO
{
    private int $id;
    private string $employeeId;
    private string $email;

    public function __construct(
        int $id,
        string $employeeId,
        string $email
    ) {
        $this->id = $id;
        $this->employeeId = $employeeId;
        $this->email = $email;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getEmployeeId(): string
    {
        return $this->employeeId;
    }

    public function getEmail(): string
    {
        return $this->email;
    }
}
