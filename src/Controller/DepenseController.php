<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\Portefeuille;
use App\Service\RepositoriesService;
use App\Service\UtilityService;
use Doctrine\ORM\EntityManagerInterface;
use React\Stream\Util;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/depenses')]
class DepenseController extends AbstractController
{
    public function __construct(
        private RepositoriesService $repositoriesService,
        private UtilityService $utilityService,
        private EntityManagerInterface $entityManager
    )
    {
    }

    #[Route('/', name: 'app_depense_index')]
    public function index(Request $request): Response
    {
        $listes = $this->repositoriesService->getAllVehiculeOccupe();
        return $this->render('portfeuille/depense_index.html.twig',[
            'listes' => $listes,
            'historique' => $this->utilityService->historiqueNavigation($request)
        ]);
    }

    #[Route('/{immatriculation}/new', name: 'app_depense_new', methods: ['GET','POST'])]
    public function new(Request $request, $immatriculation): Response
    {
        $vehicule = $this->repositoriesService->getVehiculeByImmatriculation($immatriculation);
        if (!$vehicule) {
            throw $this->createNotFoundException("Le vehicule n'a pas été trouvé!");
        }

        if ($this->isCsrfTokenValid('depenses'.$immatriculation, $request->get('_csrf_token')))
        {
            $reqObjet = $request->get('_depense_objet');
            $reqMontant = (int) $request->get('_depense_montant');
            $reqDate = $request->get('_depense_date');
            $reqDescription = $request->get('_depense_description');
            try {
                $date = new \DateTime($reqDate);
            } catch(\Exception $e){
                throw new \InvalidArgumentException("La date n'est pas valide");
            }

            // Verification des depenses du vehciule
            $verifDepense = $this->entityManager->getRepository(Portefeuille::class)->findOneBy([
                'vehicule' => $vehicule->getVehicule(),
                'date' => $date,
                'objet' => $reqObjet,
                'type' => UtilityService::SORTIE,
            ]);
            if ($verifDepense){
                $this->addFlash("danger", "Echèc! La depense a déjà été enregistrée.");
                return $this->redirectToRoute('app_depense_new',['immatriculation' => $immatriculation], Response::HTTP_SEE_OTHER);
            }

            $depense = new Portefeuille();
            $depense->setDate($date);
            $depense->setVehicule($vehicule->getVehicule());
            $depense->setObjet($reqObjet);
            $depense->setMontant($reqMontant);
            $depense->setDescription($reqDescription);
            $depense->setType(UtilityService::SORTIE);

            $this->entityManager->persist($depense);
            $this->entityManager->flush();

            $this->addFlash('success', "La dépense a été ajoutée avec succès!");

            return $this->redirectToRoute('app_portefeuille_type',[
                'type' => UtilityService::SORTIE
            ], Response::HTTP_SEE_OTHER);
        }

        return $this->render('portefeuille/depense_new.html.twig', [
            'conduire' => $vehicule,
            'historique' => $this->utilityService->historiqueNavigation($request)
        ]);
    }
}
