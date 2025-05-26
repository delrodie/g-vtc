<?php

declare(strict_types=1);

namespace App\Controller;

use App\Service\RepositoriesService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/vehicule-attribue')]
class VehiculeAttribueController extends AbstractController
{
    public function __construct(
        private RepositoriesService $repositoriesService
    )
    {
    }

    #[Route('/{chauffeur}', name: 'app_vehicule_attribue_bychauffeur', methods: ['GET'])]
    public function byChauffeur(?int $chauffeur): Response
    {
        return $this->render('chauffeur/_vehicule.html.twig',[
            'conduire' => $this->repositoriesService->getVehiculeByChauffeur($chauffeur)
        ]);
    }
}
