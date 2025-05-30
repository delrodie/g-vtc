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

    #[Route('/')]
    public function index(): Response
    {
        return $this->render('portefeuille/index.html.twig');
    }

    #[Route('/{type}', name: 'app_portefeuille_type', methods: ['GET'])]
    public function type(Request $request, $type)
    {
        // Recherche de la periode initiale
        $dateDebut = (new \DateTimeImmutable('first day of this month'))->format('Y-m-d');
        $dateFin = (new \DateTimeImmutable('today'))->format('Y-m-d');

        // Affectation de la période requestée
        $reqDatedebut = $request->query->get('date_debut');
        $reqDatefin = $request->query->get('date_fin');
        if ($reqDatedebut) $dateDebut = (new \DateTime($reqDatedebut))->format('Y-m-d');
        if ($reqDatefin) $dateFin = (new \DateTime($reqDatefin))->format('Y-m-d'); //dd($dateDebut);

        $portefeuilles = $this->repositoriesService->getOperationByTypeAndPeriode($type, $dateDebut, $dateFin);

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
