<?php

namespace App\Service;

use App\Repository\ConduireRepository;

class RepositoriesService
{
    public function __construct(
        private ConduireRepository $conduireRepository,
    )
    {
    }

    public function getVehiculeByChauffeur(?int $chauffeurId)
    {
        return $this->conduireRepository->findVehiculeByChauffeur($chauffeurId);
    }
}
