<?php

namespace App\Controller;

use App\Form\SearchDecisionsType;
use Composer\XdebugHandler\Status;
use App\Repository\DecisionRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/decision', name: 'decision_')]
class DecisionController extends AbstractController
{
    #[Route('/', name: 'index')]
    public function index(DecisionRepository $decisionRepository, Request $request): Response
    {
        $form = $this->createForm(SearchDecisionsType::class);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $title = $form->getData()['search'];
            $domaines = $form->getData()['domaines'];
            if (!empty($domaines)) {
                $domaines = $form->getData()['domaines'][0];
            }
            $status = $form->getData()['Status'];
            $decisions = $decisionRepository->search($title, $status, $domaines);
        } else {
            $decisions = $decisionRepository->findAll();
        }
        return $this->render('decision/index.html.twig', [
            'decisions' => $decisions,
            'form' => $form->createView()
        ]);
    }
}
