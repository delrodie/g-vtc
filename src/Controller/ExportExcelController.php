<?php

declare(strict_types=1);

namespace App\Controller;

use App\Service\ExportService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/export-excel')]
class ExportExcelController extends AbstractController
{
    public function __construct(
        private ExportService $exportService
    )
    {
    }

    #[Route('/{type}', name: 'app_export_excel', methods: ['GET'])]
    public function index($type): Response
    {
        $extension = date('ymdHis');
        $fileName = $type.'-'.$extension;

        if ($type = 'user'){
            $filePath = $this->exportService->userExport($fileName);
        }


        return $this->file($filePath, "$fileName.xlsx", ResponseHeaderBag::DISPOSITION_ATTACHMENT);
    }
}
