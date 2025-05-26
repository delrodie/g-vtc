<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\Conduire;
use App\Service\RepositoriesService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
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

    #[Route('/{id}', name: 'app_vehicule_retirer', methods: ['POST'])]
    public function retrait(Request $request, Conduire $conduire, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('retrait'.$conduire->getId(), $request->getPayload()->getString('_token'))) {
            $conduire->setStatut(false);
            $conduire->getVehicule()->setOccupe(false);
            $entityManager->flush();

            $this->addFlash('success', "Le véhicule a bien été retiré au chauffeur.");
        }

        return $this->redirectToRoute('app_chauffeur_show', [
            'id' => $conduire->getChauffeur()->getId()
        ], Response::HTTP_SEE_OTHER);
    }
}
