<?php

namespace App\Twig\Extension;

use App\Twig\Runtime\TwigExtensionRuntime;
use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;
use Twig\TwigFunction;

class TwigExtension extends AbstractExtension
{
    public function getFilters(): array
    {
        return [
            // If your filter generates SAFE HTML, you should add a third
            // parameter: ['is_safe' => ['html']]
            // Reference: https://twig.symfony.com/doc/3.x/advanced.html#automatic-escaping
            new TwigFilter('filter_name', [TwigExtensionRuntime::class, 'doSomething']),
            new TwigFilter('montant_abbr', [TwigExtensionRuntime::class, 'montantAbbr']),
            new TwigFilter('userrole_formatte', [TwigExtensionRuntime::class, 'userRole']),
            new TwigFilter('recette_by_vehicule', [TwigExtensionRuntime::class, 'recetteByVehicule']),
            new TwigFilter('depense_by_vehicule', [TwigExtensionRuntime::class, 'depenseByVehicule']),
            new TwigFilter('this_month_recette_by_vehicule', [TwigExtensionRuntime::class, 'thisMonthRecetteByVehicule']),
            new TwigFilter('this_month_depense_by_vehicule', [TwigExtensionRuntime::class, 'thisMonthDepenseByVehicule']),
        ];
    }

    public function getFunctions(): array
    {
        return [
            new TwigFunction('function_name', [TwigExtensionRuntime::class, 'doSomething']),
            new TwigFunction('montant_abbr', [TwigExtensionRuntime::class, 'montantAbbr']),
            new TwigFunction('userrole_formatte', [TwigExtensionRuntime::class, 'userRole']),
            new TwigFunction('recette_by_vehicule', [TwigExtensionRuntime::class, 'recetteByVehicule']),
            new TwigFunction('depense_by_vehicule', [TwigExtensionRuntime::class, 'depenseByVehicule']),
            new TwigFunction('this_month_recette_by_vehicule', [TwigExtensionRuntime::class, 'thisMonthRecetteByVehicule']),
            new TwigFunction('this_month_depense_by_vehicule', [TwigExtensionRuntime::class, 'thisMonthDepenseByVehicule']),
        ];
    }
}
