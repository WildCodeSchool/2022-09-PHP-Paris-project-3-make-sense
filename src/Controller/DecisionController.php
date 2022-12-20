<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/decision', name: 'decision_')]
class DecisionController extends AbstractController
{
    #[Route('/', name: 'decision_index')]
    public function index(): Response
    {
        return $this->render('decision/index.html.twig', [
            'controller_name' => 'DecisionController',
        ]);
    }

    #[Route('/', name: 'show')]
    public function showAll(): Response
    {
        return $this->render('decision/show_all.html.twig', [
            'controller_name' => 'DecisionController',
        ]);
    }
}
