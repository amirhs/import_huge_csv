<?php
// src/Domain/Service/CsvImportService.php
namespace App\Domain\Service;

use App\Domain\Entity\CsvFile;
use App\Domain\Entity\Employee;
use App\Domain\Repository\EmployeeRepository;

class CsvImportService
{
    private EmployeeRepository $employeeRepository;
    private string $csvUploadDir;

    public function __construct(
        EmployeeRepository $employeeRepository,
        string $csvUploadDir
    ) {
        $this->employeeRepository = $employeeRepository;
        $this->csvUploadDir = $csvUploadDir;
    }

    public function processFile(CsvFile $csvFile): void
    {
        $filePath = $this->csvUploadDir . '/' . $csvFile->getFilename();
        
        if (!file_exists($filePath)) {
            throw new \Exception("File not found: {$filePath}");
        }
        
        $handle = fopen($filePath, 'r');
        
        // Skip header row
        fgetcsv($handle);
        
        while (($data = fgetcsv($handle)) !== false) {
            $employeeId = $data[0] ?? null;
            $email = $data[1] ?? null;
            
            if (!$employeeId || !$email) {
                continue;
            }
            
            $employee = $this->employeeRepository->findByEmployeeId($employeeId);
            
            if (!$employee) {
                $employee = new Employee($employeeId, $email);
            } else {
                $employee->setEmail($email);
            }
            
            $this->employeeRepository->save($employee);
        }
        
        fclose($handle);
        
        $csvFile->setStatus('processed');
    }
}
