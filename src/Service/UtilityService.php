<?php

namespace App\Service;

class UtilityService
{
    public const ENTREE = 'ENTREE';
    public const SORTIE = 'SORTIE';
    public function historiqueNavigation($request)
    {
        $referer = $request->headers->get('referer');
        if ($referer && !str_starts_with($referer, $request->getSchemeAndHttpHost())) {
            $referer = null;
        }

        return $referer;
    }

    public function periode($request, bool $global = false): array
    {
        // Recherche de la periode initiale
        $dateDebut = (new \DateTimeImmutable('first day of this month'))->format('Y-m-d');
        $dateFin = (new \DateTimeImmutable('today'))->format('Y-m-d');

        // Affectation de la période requestée
        $reqDatedebut = $request->query->get('date_debut');
        $reqDatefin = $request->query->get('date_fin');
        if ($reqDatedebut) $dateDebut = (new \DateTime($reqDatedebut))->format('Y-m-d');
        if ($reqDatefin) $dateFin = (new \DateTime($reqDatefin))->format('Y-m-d');

        if ($global){
            $dateDebut = '2025-01-01';
        }

        return [
            'dateDebut' => $dateDebut,
            'dateFin' => $dateFin,
        ];
    }
}
