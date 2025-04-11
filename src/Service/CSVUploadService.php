<?php

namespace App\Service;

use App\Entity\CsvFile;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Component\Mime\MimeTypes;

class CSVUploadService
{
    private string $csvUploadDir;
    private Filesystem $filesystem;
    private EntityManagerInterface $entityManager;
    private SluggerInterface $slugger;

    public function __construct(
        string $csvUploadDir, 
        EntityManagerInterface $entityManager,
        SluggerInterface $slugger
    ) {
        $this->csvUploadDir = $csvUploadDir;
        $this->filesystem = new Filesystem();
        $this->entityManager = $entityManager;
        $this->slugger = $slugger;
        
        // Create upload directory if it doesn't exist
        if (!$this->filesystem->exists($this->csvUploadDir)) {
            $this->filesystem->mkdir($this->csvUploadDir);
        }
    }

    /**
     * Upload a CSV file
     * 
     * @param UploadedFile $file The uploaded CSV file
     * @return CsvFile The created CsvFile entity
     * @throws \InvalidArgumentException If the file is not a valid CSV
     * @throws FileException If there was an error uploading the file
     */
    public function upload(UploadedFile $file): CsvFile
    {
        $this->validateFile($file);
        
        // Store file information before moving it
        $originalFilename = $file->getClientOriginalName();
        $fileSize = 0;
        
        // Get file size safely
        try {
            $fileSize = $file->getSize();
        } catch (\Exception $e) {
            // If we can't get the size, we'll store 0
        }
        
        // Create a secure filename
        $safeFilename = $this->slugger->slug(pathinfo($originalFilename, PATHINFO_FILENAME));
        $filename = $safeFilename . '-' . uniqid() . '.' . $file->getClientOriginalExtension();
        
        try {
            $file->move($this->csvUploadDir, $filename);
            
            $fullPath = $this->csvUploadDir . '/' . $filename;
            if (!$this->filesystem->exists($fullPath)) {
                throw new FileException('Failed to upload file');
            }
            
            $csvFile = new CsvFile($filename);
            $csvFile->setOriginalFilename($originalFilename);
            $csvFile->setFileSize($fileSize);
            
            $this->entityManager->persist($csvFile);
            $this->entityManager->flush();
            
            return $csvFile;
        } catch (FileException $e) {
            if (isset($filename) && $this->filesystem->exists($this->csvUploadDir . '/' . $filename)) {
                $this->filesystem->remove($this->csvUploadDir . '/' . $filename);
            }
            throw $e;
        }
    }
    
    /**
     * Validate that the uploaded file is a valid CSV
     * 
     * @param UploadedFile $file The file to validate
     * @throws \InvalidArgumentException If validation fails
     */
    private function validateFile(UploadedFile $file): void
    {
        if (!$file->isValid()) {
            throw new \InvalidArgumentException('The uploaded file is not valid.');
        }

        if ($file->getClientOriginalExtension() !== 'csv') {
            throw new \InvalidArgumentException('Invalid file type. Only CSV files are allowed.');
        }
        
        try {
            $mimeTypes = new MimeTypes();
            $fileMimeType = $file->getMimeType();
            $validMimeTypes = ['text/csv', 'text/plain', 'application/csv', 'application/vnd.ms-excel'];
            
            if (!in_array($fileMimeType, $validMimeTypes)) {
                throw new \InvalidArgumentException('Invalid MIME type. File does not appear to be a CSV.');
            }
            
            $maxSize = 20 * 1024 * 1024; // 20MB
            if ($file->getSize() > $maxSize) {
                throw new \InvalidArgumentException('File is too large. Maximum size is 20MB.');
            }
        } catch (\Exception $e) {
            throw new \InvalidArgumentException('Cannot validate file: ' . $e->getMessage());
        }
    }
}