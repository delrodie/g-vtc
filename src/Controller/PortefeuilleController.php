<?php

declare(strict_types=1);

namespace App\Controller;

use App\Service\RepositoriesService;
use App\Service\UtilityService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/portefeuille')]
class PortefeuilleController extends AbstractController
{
    public function __construct(
        private RepositoriesService $repositoriesService,
        private UtilityService $utilityService,
    )
    {
    }

    #[Route('/', name: 'app_portefeuille_stats')]
    public function stats(Request $request): Response
    {
        $periode = $this->utilityService->periode($request);
//        $recette = ;
        return $this->render('portefeuille/stats.html.twig',[
            'historique' => $this->utilityService->historiqueNavigation($request),
            'recette_mois' => $this->repositoriesService->getMontantByTypeAndPeriode(UtilityService::ENTREE, $periode['dateDebut'], $periode['dateFin']),
            'depense_mois' => $this->repositoriesService->getMontantByTypeAndPeriode(UtilityService::SORTIE, $periode['dateDebut'], $periode['dateFin']),
            'recette_totale' => $this->repositoriesService->getMontantByTypeAndPeriode(UtilityService::ENTREE, '2025-01-01', $periode['dateFin']),
            'depense_totale' => $this->repositoriesService->getMontantByTypeAndPeriode(UtilityService::SORTIE, '2025-01-01', $periode['dateFin']),
        ]);
    }

    #[Route('/{type}', name: 'app_portefeuille_type', methods: ['GET'])]
    public function type(Request $request, $type)
    {
        $periode = $this->utilityService->periode($request);

        $portefeuilles = $this->repositoriesService->getOperationByTypeAndPeriode($type, $periode['dateDebut'], $periode['dateFin']);

        return $this->render('portefeuille/liste.html.twig', [
            'portefeuilles' => $portefeuilles,
            'historique' => $this->utilityService->historiqueNavigation($request),
            'type' => strtoupper($type) === 'ENTREE' ? 'Recettes' : 'Dépenses'
        ]);
    }

    #[Route('/{code}/suppression', name: 'app_portefeuille_suppression', methods: ['DELETE'] )]
    public function suppression(Request $request, $code, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$code, $request->headers->get('X-CSRF-Token'))){
            $operation = $this->repositoriesService->getOperationByCode($code);
            if (!$operation){
                throw $this->createNotFoundException("Aucune opération trouvée!");
            }
            $entityManager->remove($operation);
            $entityManager->flush();

            $this->addFlash('success',"Félicitations! L'opération a été supprimée avec succès!");

            return $this->json(['success'=> true]);
        }

        return $this->json(['success' => false, 'message'=> 'Token CSRF invalide'], Response::HTTP_FORBIDDEN);
    }
}
