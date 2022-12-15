<?php

namespace App\Controller;

use App\Entity\DecisionHistory;
use App\Form\DecisionHistoryType;
use App\Repository\DecisionHistoryRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/decision-history')]
class DecisionHistoryController extends AbstractController
{
    #[Route('/', name: 'app_decision_history_index', methods: ['GET'])]
    public function index(DecisionHistoryRepository $decisionHistoryRepository): Response
    {
        return $this->render('decision_history/index.html.twig', [
            'decision_histories' => $decisionHistoryRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_decision_history_new', methods: ['GET', 'POST'])]
    public function new(Request $request, DecisionHistoryRepository $decisionHistoryRepository): Response
    {
        $decisionHistory = new DecisionHistory();
        $form = $this->createForm(DecisionHistoryType::class, $decisionHistory);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $decisionHistoryRepository->save($decisionHistory, true);

            return $this->redirectToRoute('app_decision_history_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('decision_history/new.html.twig', [
            'decision_history' => $decisionHistory,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_decision_history_show', methods: ['GET'])]
    public function show(DecisionHistory $decisionHistory): Response
    {
        return $this->render('decision_history/show.html.twig', [
            'decision_history' => $decisionHistory,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_decision_history_edit', methods: ['GET', 'POST'])]
    public function edit(
        Request $request,
        DecisionHistory $decisionHistory,
        DecisionHistoryRepository $decisionHistoryRepository
    ): Response {
        $form = $this->createForm(DecisionHistoryType::class, $decisionHistory);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $decisionHistoryRepository->save($decisionHistory, true);

            return $this->redirectToRoute('app_decision_history_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('decision_history/edit.html.twig', [
            'decision_history' => $decisionHistory,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_decision_history_delete', methods: ['POST'])]
    public function delete(
        Request $request,
        DecisionHistory $decisionHistory,
        DecisionHistoryRepository $decisionHistoryRepository
    ): Response {
        if ($this->isCsrfTokenValid('delete' . $decisionHistory->getId(), $request->request->get('_token'))) {
            $decisionHistoryRepository->remove($decisionHistory, true);
        }

        return $this->redirectToRoute('app_decision_history_index', [], Response::HTTP_SEE_OTHER);
    }
}
