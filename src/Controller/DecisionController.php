<?php

namespace App\Controller;

use App\Entity\Decision;
use App\Form\SearchTitleType;
use PhpParser\Builder\Method;
use App\Form\SearchDecisionsType;
use App\Controller\HomeController;
use Composer\XdebugHandler\Status;
use App\Repository\DecisionRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/decision', name: 'decision_')]
class DecisionController extends AbstractController
{
    #[Route('/{title?}', name: 'search')]
    public function search(DecisionRepository $decisionRepository, Request $request, ?string $title): Response
    {
        if (!empty($request->request->all())) {
            $title = $request->request->all()['search_decisions']['search'];
        }
        $form = $this->createForm(SearchDecisionsType::class, ['title' => $title]);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $decisions = $decisionRepository->search(
                $form->getData()['search'],
                $form->getData()['status'],
                $form->getData()['departements']
            );
        } else {
            $decisions = $decisionRepository->search($title, Decision::STATUS_ALL);
        }
        return $this->render('decision/index.html.twig', [
            'decisions' => $decisions,
            'form' => $form->createView(),
            'title' => $title
        ]);
    }
}
