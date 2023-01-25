<?php

namespace App\Controller;

use App\Form\SearchDecisionsType;
use App\Controller\HomeController;
use Composer\XdebugHandler\Status;
use App\Repository\DecisionRepository;
use PhpParser\Builder\Method;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/decision', name: 'decision_')]
class DecisionController extends AbstractController
{
    #[Route('/{title}', name: 'index')]
    public function index(DecisionRepository $decisionRepository, Request $request, string $title): Response
    {
        $form = $this->createForm(SearchDecisionsType::class, ['title' => $title]);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $title = $form->getData()['search'];
            $domaines = $form->getData()['departements'];
            $status = $form->getData()['Status'];
            if (!empty($domaines)) {
                $domaines = $form->getData()['departements'][0];
            } elseif (empty($domaines)) {
                $domaines = null;
            } if (!empty($status)) {
                $status = $form->getData()['Status'];
            } elseif (empty($status)) {
                $status = null;
            }
            $decisions = $decisionRepository->search($title, $status, $domaines);
        } else {
            $decisions = $decisionRepository->findAll();
        }
        return $this->render('decision/index.html.twig', [
            'decisions' => $decisions,
            'form' => $form->createView(),
            'title' => $title
        ]);
    }
}
