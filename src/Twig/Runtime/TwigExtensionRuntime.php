<?php

namespace App\Twig\Runtime;

use Twig\Extension\RuntimeExtensionInterface;

class TwigExtensionRuntime implements RuntimeExtensionInterface
{
    public function __construct()
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
}
