<?php

declare(strict_types=1);

namespace App\Controller;

use App\Service\RepositoriesService;
use App\Service\UtilityService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class HomeController extends AbstractController
{
    public function __construct(
        private RepositoriesService $repositoriesService,
        private UtilityService $utilityService
    )
    {
    }

    #[Route('/', name:'app_home')]
    public function index(Request $request): Response
    {
        $periode = $this->utilityService->periode($request);
        return $this->render('home/index.html.twig',[
            'recette_mois' => $this->repositoriesService->getMontantByTypeAndPeriode(UtilityService::ENTREE, $periode['dateDebut'], $periode['dateFin']),
            'depense_mois' => $this->repositoriesService->getMontantByTypeAndPeriode(UtilityService::SORTIE, $periode['dateDebut'], $periode['dateFin']),
            'vehicules' => $this->repositoriesService->getAllVehiculeOccupe()
        ]);
    }
}
