<?php

namespace App\Controller;

use App\Form\SearchDecisionType;
use App\Repository\DecisionRepository;
use Composer\XdebugHandler\Status;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

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

    #[Route('/show_all', name: 'show_all')]
    public function showAll(DecisionRepository $decisionRepository, Request $request): Response
    {
        // dd($decisionRepository->findLikeDomainName());
        $form = $this->createForm(SearchDecisionType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $search = $form->getData()['search'];
            $decisions = $decisionRepository->findLikeTitle($search);
        } else {
            $decisions = $decisionRepository->findAll();
        }
        return $this->render('decision/show_all.html.twig', [
            'decisions' => $decisions,
            'form' => $form->createView()
        ]);
    }
}
