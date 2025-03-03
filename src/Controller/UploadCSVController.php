<?php

namespace App\Controller;

use App\Service\CSVUploadService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class UploadCSVController extends AbstractController
{
    private CSVUploadService $uploadService;

    public function __construct(CSVUploadService $uploadService)
    {
        $this->uploadService = $uploadService;
    }

    #[Route('/upload/csv', methods: ['POST'])]
    public function upload(Request $request): JsonResponse
    {
        $file = $request->files->get('file');

        if (!$file) {
            return new JsonResponse(['error' => 'No file uploaded'], 400);
        }

        try {
            $this->uploadService->upload($file);
            return new JsonResponse(['uploadFileSuccessfully' => true]);
        } catch (\Exception $e) {
            return new JsonResponse(['error' => $e->getMessage()], 400);
        }
    }
}
