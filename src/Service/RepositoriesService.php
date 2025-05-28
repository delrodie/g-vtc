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

    public function getOperationByTypeAndPeriode($type, $dateDebut, $dateFin)
    {
        return $this->portefeuilleRepository->findOperationByTypeAndPeriode($type, $dateDebut, $dateFin);
    }
}
