<?php

namespace App\Twig\Runtime;

use App\Service\RepositoriesService;
use App\Service\UtilityService;
use Twig\Extension\RuntimeExtensionInterface;

class TwigExtensionRuntime implements RuntimeExtensionInterface
{
    public function __construct(
        private RepositoriesService $repositoriesService
    )
    {
        // Inject dependencies if needed
    }

    public function doSomething($value)
    {
        // ...
    }

    public function montantAbbr($value)
    {
        if ($value >= 1000000000){
            $nombre = $value / 1000000000;
            $suffix = 'B';
        }elseif ( $value >= 1000000){
            $nombre = $value / 1000000;
            $suffix = 'M';
        }elseif ($value >= 1000){
            $nombre = $value / 1000;
            $suffix = 'K';
        }else{
            return number_format($value, 0, '.', '');
        }

        // Suppression des 0 inutiles
        $formate = rtrim(rtrim(number_format($nombre, 2, '.', ''), '0'), '.');

        return $formate.$suffix;
    }

    public function userRole($value): string
    {
        $roles = [
            'ROLE_SUPER_ADMIN' => "Super Admin",
            'ROLE_ADMIN' => "Admin",
            'ROLE_USER' => "Utilisateur"
        ];

        foreach ($roles as $role => $label) {
            if (in_array($role, $value, true)) {
                return $label;
            }
        }

        return "Inconnu";
    }

    public function recetteByVehicule($value)
    {
        return $this->repositoriesService->getMontantByTypeAndVehicule(UtilityService::ENTREE, $value);
    }

    public function depenseByVehicule($value)
    {
        return $this->repositoriesService->getMontantByTypeAndVehicule(UtilityService::SORTIE, $value);
    }

    public function thisMonthRecetteByVehicule($value)
    {
        return $this->repositoriesService->getMontantByTypeVehiculeAndPeriode(UtilityService::ENTREE, $value, $this->thisMonth());
    }

    public function thisMonthDepenseByVehicule($value)
    {
        return $this->repositoriesService->getMontantByTypeVehiculeAndPeriode(UtilityService::SORTIE, $value, $this->thisMonth());
    }

    private function thisMonth(): array
    {
        return [
            'dateDebut' => (new \DateTimeImmutable('first day of this month'))->format('Y-m-d'),
            'dateFin' => (new \DateTimeImmutable('today'))->format('Y-m-d'),
        ];
    }
}
