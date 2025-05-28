<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\Chauffeur;
use App\Entity\Portefeuille;
use App\Entity\Vehicule;
use App\Service\RepositoriesService;
use App\Service\UtilityService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/recette')]
class RecetteController extends AbstractController
{
    public function __construct(
        private RepositoriesService $repositoriesService,
        private UtilityService $utilityService,
    )
    {
    }

    #[Route('/', name: 'app_recette_form')]
    public function form(Request $request): Response
    {
        $listes = $this->repositoriesService->getAllVehiculeOccupe(); //dd($listes);

        return $this->render('portefeuille/recette_new.html.twig',[
            'listes' => $listes,
            'historique' => $this->utilityService->historiqueNavigation($request)
        ]);
    }

    #[Route('/{type}', name: 'app_recette_list', methods: ['GET'])]
    public function list(Request $request, $type): Response
    {
        // Recherche de la periode initiale
        $dateDebut = new \DateTimeImmutable('first day of this month');
        $dateFin = new \DateTimeImmutable('today');

        // Affectation de la période requestée
        $reqDatedebut = $request->query->get('date_debut');
        $reqDatefin = $request->query->get('date_fin');
        if ($reqDatedebut) $dateDebut = new \DateTime($reqDatedebut);
        if ($reqDatefin) $dateFin = new \DateTime($reqDatefin);

        $recettes = $this->repositoriesService->getOperationByTypeAndPeriode(UtilityService::ENTREE, $dateDebut, $dateFin);

        return $this->render('portefeuille/recette_list.html.twig', [
            'recettes' => $recettes
        ]);
    }

    #[Route('/{immatriculation}/modif', name: 'app_recette_modif')]
    public function modif(Request $request, $immatriculation)
    {
        try {
            $vehicule = $this->repositoriesService->getVehiculeByImmatriculation($immatriculation);
        }catch (\Exception $exception){
            throw new NotFoundHttpException("Le vehicule recherché n'a pas été trouvé");
        }

        return $this->render('portefeuille/recette_modif.html.twig',[
            'conduire' => $vehicule,
            'historique' => $this->utilityService->historiqueNavigation($request)
        ]);

    }

    #[Route('/{id}', name: 'app_recette_validation', methods: ['GET', 'POST'])]
    public function validation(Request $request, Vehicule $vehicule, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('recette'.$vehicule->getId(), $request->getPayload()->getString('_csrf_token')))
        {
            // Gerer la date
            $requestDate = $request->get('_recette_date');
            $requestMontant = (int) $request->get('_recette_montant');
            try {
                $date = new \DateTime($requestDate);
            } catch(\Exception $e){
                throw new \InvalidArgumentException("Date invalide");
            }

            // Verification de non existance de la recette correspondante au vehicule à cette date
            $verifRecette = $entityManager->getRepository(Portefeuille::class)->findOneBy([
                'vehicule' => $vehicule,
                'date' => $date,
                'type' => UtilityService::ENTREE
            ]);
            if ($verifRecette){
                $this->addFlash('danger', "Echèc! La recette du vehicule '{$vehicule->getImmatriculation()}' à cette date a déjà été enregistrée.");
                return $this->redirectToRoute('app_recette_new',[], Response::HTTP_SEE_OTHER);
            }

            $recette = new Portefeuille();
            $recette->setVehicule($vehicule);
            $recette->setMontant($requestMontant);
            $recette->setType(UtilityService::ENTREE);
            $recette->setDate($date);

            $entityManager->persist($recette);
            $entityManager->flush();

            $this->addFlash('success', "La recette du véhicule '{$vehicule->getImmatriculation()}' a été ajoutée avec succès!");
            return $this->redirectToRoute('app_recette_list',[
                'type' => UtilityService::ENTREE
            ], Response::HTTP_SEE_OTHER);
        }

        return $this->redirectToRoute('app_recette_form',[], Response::HTTP_SEE_OTHER);
    }

    #[Route('/{code}/suppression', name: 'app_recette_suppression', methods: ['POST'] )]
    public function suppression(Request $request, $code, EntityManagerInterface $entityManager): Response
    {
        try {
            $recette = $this->repositoriesService->getOperationByCode($code);
        }catch(\Exception $exception){
            throw new NotFoundHttpException("L'opération recherchée n'a pas été trouvée!");
        }

        $entityManager->remove($recette);
        $entityManager->flush();

        $this->addFlash('success',"Félicitations! La recette a été supprimée avec succès!");

        return $this->redirectToRoute('app_recette_list', [
            'type' => UtilityService::ENTREE,
        ], Response::HTTP_SEE_OTHER);
    }
}
