<?php
// src/Presentation/Controller/CsvController.php
namespace App\Presentation\Controller;

use App\Application\Bus\CommandBusInterface;
use App\Application\Bus\QueryBusInterface;
use App\Application\Command\UploadCsvCommand;
use App\Application\Query\GetCsvFileStatusQuery;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CsvController extends AbstractController
{
    private CommandBusInterface $commandBus;
    private QueryBusInterface $queryBus;

    public function __construct(
        CommandBusInterface $commandBus,
        QueryBusInterface $queryBus
    ) {
        $this->commandBus = $commandBus;
        $this->queryBus = $queryBus;
    }

    /**
     * @Route("/upload", name="csv_upload", methods={"POST"})
     */
    public function upload(Request $request): Response
    {
        $file = $request->files->get('csv_file');
        
        if (!$file) {
            return $this->json(['error' => 'No file uploaded'], Response::HTTP_BAD_REQUEST);
        }
        
        try {
            $fileId = $this->commandBus->dispatch(new UploadCsvCommand($file));
            
            return $this->json(['id' => $fileId], Response::HTTP_CREATED);
        } catch (\Exception $e) {
            return $this->json(['error' => $e->getMessage()], Response::HTTP_BAD_REQUEST);
        }
    }

    /**
     * @Route("/status/{id}", name="csv_status", methods={"GET"})
     */
    public function status(string $id): Response
    {
        try {
            $status = $this->queryBus->handle(new GetCsvFileStatusQuery($id));
            
            return $this->json([
                'id' => $status->getId(),
                'filename' => $status->getFilename(),
                'status' => $status->getStatus(),
                'uploaded_at' => $status->getUploadedAt()->format('Y-m-d H:i:s')
            ]);
        } catch (\Exception $e) {
            return $this->json(['error' => $e->getMessage()], Response::HTTP_NOT_FOUND);
        }
    }
}
