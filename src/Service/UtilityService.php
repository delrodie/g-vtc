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
}
