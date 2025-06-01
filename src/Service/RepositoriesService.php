<?php

namespace App\Service;

use App\Repository\ConduireRepository;
use App\Repository\PortefeuilleRepository;
use App\Repository\VehiculeRepository;

class RepositoriesService
{
    public const ENTREE = 'ENTREE';
    public const SORTIE = 'SORTIE';
    public function __construct(
        private ConduireRepository $conduireRepository,
        private VehiculeRepository $vehiculeRepository, private readonly PortefeuilleRepository $portefeuilleRepository,
    )
    {
    }

    public function getVehiculeByChauffeur(?int $chauffeurId)
    {
        return $this->conduireRepository->findVehiculeByChauffeur($chauffeurId);
    }

    public function getAllVehiculesByChauffeur(?int $chauffeur)
    {
        return $this->conduireRepository->findAllVehiculeConduitsByChauffeur($chauffeur);
    }

    public function getAllVehiculeOccupe()
    {
        return $this->conduireRepository->findAllVehiculeOccupe();
    }

    public function getVehiculeByImmatriculation($immatriculation)
    {
        return $this->conduireRepository->findOneByImmatriculation($immatriculation);
    }

    public function getOperationByTypeAndPeriode($type, $dateDebut, $dateFin)
    { //dd($dateFin);
        return $this->portefeuilleRepository->findOperationByTypeAndPeriode($type, $dateDebut, $dateFin);
    }

    public function getOperationByPeriode($periode)
    {
        return $this->portefeuilleRepository->findOperationByPeriode($periode);
    }

    public function getOperationByCode($code)
    {
        return $this->portefeuilleRepository->findOneBy(['code' => $code]);
    }

    public function getMontantByTypeAndPeriode($type, $dateDebut, $dateFin)
    {
        return $this->portefeuilleRepository->findMontantByTypeAndPeriode($type, $dateDebut, $dateFin);
    }

    public function getMontantMensuelByTypeAndAnnee($type, $annee = null)
    {
        if (!$annee) $annee = (new \DateTime())->format('Y');
        $montants = $this->portefeuilleRepository->findMontantMensuelByTypeAndAnnee($type, $annee);

        return $this->genererTableauMensuel($montants);
    }

    public function getMontantByTypeAndVehicule($type, $vehicule)
    {
        return $this->portefeuilleRepository->findmontantByTypeAndVehicule($type, $vehicule);
    }

    public function getMontantByTypeVehiculeAndPeriode($type, $vehicule, $periode)
    {
        return $this->portefeuilleRepository->findMontantByTypeVehiculeAndPeriode($type, $vehicule, $periode);
    }

    private function genererTableauMensuel(array $resultats): array
    {
        $data = array_fill(1, 12, 0);
        foreach ($resultats as $row){
            $mois = (int) $row['mois'];
            $data[$mois] = (int)$row['montant'];
        }

        return array_values($data);
    }
}
