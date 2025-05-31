<?php

declare(strict_types=1);

namespace App\Controller;

use App\Service\RepositoriesService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\UX\Chartjs\Builder\ChartBuilderInterface;
use Symfony\UX\Chartjs\Model\Chart;

class ChartjsController extends AbstractController
{
    public function __construct(
        private RepositoriesService $repositoriesService
    )
    {
    }

    #[Route('/chartjs', name: 'app_chartjs')]
    public function index(ChartBuilderInterface $chartBuilder): Response
    {
        $recette = $this->repositoriesService->getMontantMensuelByTypeAndAnnee('entree');
        $depense = $this->repositoriesService->getMontantMensuelByTypeAndAnnee('sortie');
        $chart = $chartBuilder->createChart(Chart::TYPE_BAR);
        $chart->setData([
            'labels' => ['Jan', 'Fev', 'Mars', 'Avr', 'Juin', 'Juil', 'Aout', 'Sep', 'Oct', 'Nov', 'Dec'],
            'datasets' => [
                [
                    'label' => 'Recettes',
                    'backgroundColor' => 'rgb(25,150,118, .5)',
                    'borderColor' => 'rgb(25,150,118)',
                    'data' => $recette,
                    'tension' => 0.4,
                    'stack' => 'Montant'
                ],
                [
                    'label' => 'Dépenses',
                    'backgroundColor' => 'rgb(255,99,132, .5)',
                    'borderColor' => 'rgb(255,99,132)',
                    'data' => $depense,
                    'tension' => 0.4,
                    'stack' => 'Montant'
                ],
                [
                    'label' => 'Bénéfice',
                    'backgroundColor' => 'rgba(54, 162, 235, 0.6)',
                    'borderColor' => 'rgb(54, 162, 235)',
                    'data' => array_map(fn($r, $d) => $r - $d, $recette, $depense),
                    'type' => 'line', // pour le voir en ligne sur les barres
                    'fill' => false,
                    'yAxisID' => 'y', // même axe
                ]
            ]
        ]);
        $chart->setOptions([
            'responsive' => true,
            'maintainAspectRatio' => false,
            'scales' => [
                'x' => [
                    'stacked' => true,
                ],
                'y' => [
                    'stacked' => true,
                ],
            ],
        ]);
        return $this->render('home/chartjs.html.twig',[
            'chart' => $chart
        ]);
    }
}
