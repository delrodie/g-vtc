<?php

namespace App\Controller;

use App\Entity\Chauffeur;
use App\Entity\Conduire;
use App\Form\ChauffeurForm;
use App\Repository\ChauffeurRepository;
use App\Service\RepositoriesService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/chauffeur')]
final class ChauffeurController extends AbstractController
{
    public function __construct(
        private RepositoriesService $repositoriesService,
    )
    {
    }

    #[Route(name: 'app_chauffeur_index', methods: ['GET'])]
    public function index(ChauffeurRepository $chauffeurRepository): Response
    {
        return $this->render('chauffeur/index.html.twig', [
            'chauffeurs' => $chauffeurRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_chauffeur_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $chauffeur = new Chauffeur();
        $form = $this->createForm(ChauffeurForm::class, $chauffeur);
        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            if ($form->isValid()) {
                $entityManager->persist($chauffeur);

                $nouvelleAttribution = $form->get('nouvelleAttribution')->getData();
                if ($nouvelleAttribution instanceof Conduire){
                    $nouvelleAttribution->setChauffeur($chauffeur);

                    // Verification du statut du vehicule avant
                    $vehicule = $nouvelleAttribution->getVehicule();
                    if ($vehicule->isOccupe() === true ){
                        $this->addFlash('danger', "Echec, Le vehicule {$vehicule->getImmatriculation()} est déjà occupé par un autre chauffeur");
                        return $this->redirectToRoute('app_chauffeur_new');
                    }

                    $nouvelleAttribution->getVehicule()->setOccupe(true);

                    $entityManager->persist($nouvelleAttribution);
                }
                $entityManager->flush();

                $this->addFlash("success", "Chauffeur ajouté avec succès!");

                return $this->redirectToRoute('app_chauffeur_index', [], Response::HTTP_SEE_OTHER);
            }else{
                foreach ($form->getErrors(true) as $error) {
                    $this->addFlash('danger', $error->getMessage());
                }
            }
        }

        return $this->render('chauffeur/new.html.twig', [
            'chauffeur' => $chauffeur,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_chauffeur_show', methods: ['GET'])]
    public function show(Chauffeur $chauffeur): Response
    {
        $conduire = $this->repositoriesService->getVehiculeByChauffeur($chauffeur->getId());
        //dd($vehicule);
        return $this->render('chauffeur/show.html.twig', [
            'chauffeur' => $chauffeur,
            'conduire' => $conduire
        ]);
    }

    #[Route('/{id}/edit', name: 'app_chauffeur_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Chauffeur $chauffeur, EntityManagerInterface $entityManager): Response
    {
        // L'entité conduire associée au chauffeur
        $ancienneAttribution = $entityManager->getRepository(Conduire::class)->findOneBy([
            'chauffeur' => $chauffeur,
            'statut' => true
        ]);

        $form = $this->createForm(ChauffeurForm::class, $chauffeur,[
            'conduire' => $ancienneAttribution
        ]);
        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            if ($form->isValid()) {

                $nouvelleAttribution = $form->get('nouvelleAttribution')->getData();
                if ($nouvelleAttribution instanceof Conduire){
                    $nouvelleAttribution->setChauffeur($chauffeur);

                    // Verification si le vehicule a changé
                    if ($ancienneAttribution)
                    {
                        // Si les immatricules sont différents alors affecter les nouvelles valeurs a conduire
                        // Sinon effectué les modifications dans la ligne conduire actuel
                        if ( $nouvelleAttribution->getVehicule()->getImmatriculation() !== $ancienneAttribution->getVehicule()->getImmatriculation())
                        {
                            // Verification du statut du nouveau vehicule
                            $vehicule = $nouvelleAttribution->getVehicule();
                            if ($vehicule->isOccupe() === true ){
                                $this->addFlash('danger', "Echec, Le vehicule {$vehicule->getImmatriculation()} est déjà occupé par un autre chauffeur");
                                return $this->redirectToRoute('app_chauffeur_new');
                            }
                            // Changer le statut de l'ancien vehicule en inoccupé
                            $ancienVehicule = $ancienneAttribution->getVehicule();
                            $ancienVehicule->setOccupe(0);
                            $entityManager->persist($ancienVehicule);

                            // Affectation des nouvelles valeurs
                            $nouveauVehicule = $nouvelleAttribution->getVehicule();
                            $ancienneAttribution->setVehicule($nouveauVehicule);
                            $ancienneAttribution->setStatut($nouvelleAttribution->getStatut());
                            $ancienneAttribution->setDateDebutAt($nouvelleAttribution->getDateDebutAt());
                            $entityManager->persist($ancienneAttribution);

                            $nouveauVehicule->setOccupe(true);
                            $entityManager->persist($nouveauVehicule);
                        }else{
                            if ($nouvelleAttribution->isStatut() === false){
                                $vehicule = $nouvelleAttribution->getVehicule();
                                $vehicule->setOccupe(false);
                                $entityManager->persist($vehicule);
                            }
                        }
                    }else{
                        // Verification du statut du nouveau vehicule
                        $vehicule = $nouvelleAttribution->getVehicule();
                        if ($vehicule->isOccupe() === true ){
                            $this->addFlash('danger', "Echec, Le vehicule {$vehicule->getImmatriculation()} est déjà occupé par un autre chauffeur");
                            return $this->redirectToRoute('app_chauffeur_new');
                        }

                        $vehicule->setOccupe(true);
                        $entityManager->persist($vehicule);
                        $entityManager->persist($nouvelleAttribution);
                    }

                }
                $entityManager->flush();

                $this->addFlash("success", "Chauffeur ajouté avec succès!");

                return $this->redirectToRoute('app_chauffeur_index', [], Response::HTTP_SEE_OTHER);
            }else{
                foreach ($form->getErrors(true) as $error) {
                    $this->addFlash('danger', $error->getMessage());
                }
            }

        }

        return $this->render('chauffeur/edit.html.twig', [
            'chauffeur' => $chauffeur,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_chauffeur_delete', methods: ['POST'])]
    public function delete(Request $request, Chauffeur $chauffeur, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$chauffeur->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($chauffeur);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_chauffeur_index', [], Response::HTTP_SEE_OTHER);
    }

    private function verifStatutVehicule($vehicule)
    {

    }
}
