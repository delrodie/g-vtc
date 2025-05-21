<?php

namespace App\Service;

use App\Repository\UserRepository;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ExportService
{
    public function __construct(
        private UserRepository $userRepository,
    )
    {
    }

    public function userExport(?string $fileName = null)
    {
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setCellValue('A1', 'ID');
        $sheet->setCellValue('B1', 'EMAIL');
        $sheet->setCellValue('C1', 'ROLE');
        $sheet->setCellValue('D1', 'CONNEXION');
        $sheet->setCellValue('E1', 'DERNIERE CONNEXION');

        $users = $this->userRepository->findAll();
        $row= 2; $i=1;
        foreach ($users as $user) {
            $sheet->setCellValue("A$row", $i);
            $sheet->setCellValue("B$row", $user->getEmail());
            $sheet->setCellValue("C$row", 'Administrateur');
            $sheet->setCellValue("D$row", $user->getConnexion());
            $sheet->setCellValue("E$row", $user->getLastConnectedAt()?->format('Y-m-d H:i:s'));
            $row++;
            $i++;
        }

        $writer = new Xlsx($spreadsheet);
        $filePath = sys_get_temp_dir() . "/$fileName.xlsx";
        $writer = new Xlsx($spreadsheet);
        $writer->save($filePath);

        return $filePath;

    }
}
