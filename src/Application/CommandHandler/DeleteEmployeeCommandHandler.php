<?php
// src/Application/CommandHandler/DeleteEmployeeCommandHandler.php
namespace App\Application\CommandHandler;

use App\Application\Command\DeleteEmployeeCommand;
use App\Domain\Repository\EmployeeRepository;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;

class DeleteEmployeeCommandHandler implements MessageHandlerInterface
{
    private EmployeeRepository $employeeRepository;

    public function __construct(EmployeeRepository $employeeRepository)
    {
        $this->employeeRepository = $employeeRepository;
    }

    public function __invoke(DeleteEmployeeCommand $command): void
    {
        $employee = $this->employeeRepository->findByEmployeeId($command->getEmployeeId());
        
        if (!$employee) {
            throw new \Exception("Employee with EmployeeID {$command->getEmployeeId()} does not exist.");
        }
        
        $this->employeeRepository->remove($employee);
    }
}
