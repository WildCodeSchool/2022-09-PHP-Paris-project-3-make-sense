<?php

namespace App\Controller;

use App\Repository\DecisionRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    #[Route('/', name: 'app_home')]
    public function index(DecisionRepository $decisionRepository): Response
    {
        return $this->render(
            'home/index.html.twig',
            [
                'allFirstDecisionCreated' => $decisionRepository->findBy(
                    [],
                    ['createdAt' => 'DESC'],
                    3,
                    0
                ),
                'myFirstDecisionCreated' => $decisionRepository->findBy(
                    ['owner' => 178],
                    ['createdAt' => 'DESC'],
                    3,
                    0
                ),

            ]
        );
    }
}
