<?php

namespace App\Controller;

use App\Service\OpinionLike;
use App\Form\SearchDecisionType;
use Composer\XdebugHandler\Status;
use App\Repository\DecisionRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * This will suppress all the PMD warnings in
 * this class.
 *
 * @SuppressWarnings(PHPMD)
 */
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
    public function showAll(DecisionRepository $decisionRepository, Request $request, OpinionLike $opinion): Response
    {
        $form = $this->createForm(SearchDecisionType::class);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $title = $form->getData()['search'];
            if ($domaines = $form->getData()['domaines'][0] !== null) {
                $domaines = $form->getData()['domaines'][0]->getName();
            } else {
                $domaines = null;
            }
            $status = $form->getData()['Status'];
            $decisions = $decisionRepository->search($title, $status, $domaines);
        } else {
            $decisions = $decisionRepository->findAll();
        }
        return $this->render('decision/show_all.html.twig', [
            'decisions' => $decisions,
            'form' => $form->createView()
        ]);
    }
}
