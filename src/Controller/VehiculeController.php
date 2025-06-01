<?php

namespace App\Controller;

use App\Entity\Vehicule;
use App\Form\VehiculeForm;
use App\Repository\VehiculeRepository;
use App\Service\UtilityService;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/vehicule')]
final class VehiculeController extends AbstractController
{
    public function __construct(
        private VehiculeRepository $vehiculeRepository,
        private UtilityService $utilityService
    )
    {
    }

    #[Route(name: 'app_vehicule_index', methods: ['GET'])]
    public function index(Request $request, VehiculeRepository $vehiculeRepository): Response
    {
        return $this->render('vehicule/index.html.twig', [
            'vehicules' => $vehiculeRepository->getAllVehicule(),
            'historique' => $this->utilityService->historiqueNavigation($request)
        ]);
    }

    #[Route('/new', name: 'app_vehicule_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $vehicule = new Vehicule();
        $form = $this->createForm(VehiculeForm::class, $vehicule);
        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            if ($form->isValid()) {
                $entityManager->persist($vehicule);
                $entityManager->flush();

                $this->addFlash("success", "Le vehicule immatriculé {$vehicule->getImmatriculation()} a été ajouté avec succès!");

                return $this->redirectToRoute('app_vehicule_index', [], Response::HTTP_SEE_OTHER);
            }else{
                foreach ($form->getErrors(true) as $error) {
                    $this->addFlash('danger', $error->getMessage());
                }
            }
        }

        return $this->render('vehicule/new.html.twig', [
            'vehicule' => $vehicule,
            'form' => $form,
            'historique' => $this->utilityService->historiqueNavigation($request)
        ]);
    }

    #[Route('/{slug}', name: 'app_vehicule_show', methods: ['GET'])]
    public function show(Request $request, $slug): Response
    {

        return $this->render('vehicule/show.html.twig', [
            'vehicule' => $this->getVehicule($slug),
            'historique' => $this->utilityService->historiqueNavigation($request)
        ]);
    }

    #[Route('/{slug}/edit', name: 'app_vehicule_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, $slug, EntityManagerInterface $entityManager): Response
    {
        $vehicule = $this->getVehicule($slug);
        $form = $this->createForm(VehiculeForm::class, $vehicule);
        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            if ($form->isValid()){
                $entityManager->flush();

                $this->addFlash("info", "Le vehicule immatriculé {$vehicule->getImmatriculation()} a été modifié avec succès!");
                return $this->redirectToRoute('app_vehicule_index', [], Response::HTTP_SEE_OTHER);
            } else{
                foreach ($form->getErrors(true) as $error) {
                    $this->addFlash('danger', $error->getMessage());
                }
            }

        }

        return $this->render('vehicule/edit.html.twig', [
            'vehicule' => $vehicule,
            'form' => $form,
            'historique' => $this->utilityService->historiqueNavigation($request)
        ]);
    }

    #[Route('/{id}', name: 'app_vehicule_delete', methods: ['POST'])]
    public function delete(Request $request, Vehicule $vehicule, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$vehicule->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($vehicule);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_vehicule_index', [], Response::HTTP_SEE_OTHER);
    }

    private function getVehicule($slug)
    {
        $vehicule = $this->vehiculeRepository->getVehiculeBySlug($slug);
        if (!$vehicule) {
            throw new NotFoundHttpException("Aucun vehicule trouvé!");
        }
        return $vehicule;
    }
}
