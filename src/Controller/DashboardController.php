<?php

namespace App\Controller;

use App\Entity\Decision;
use App\Service\OpinionLike;
use App\Form\SearchTitleType;
use App\Repository\DecisionRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class DashboardController extends AbstractController
{
    #[Route('/dashboard', name: 'app_dashboard')]
    public function index(
        DecisionRepository $decisionRepository,
        Request $request
    ): Response {

        /** @var \App\Entity\User */
        $user = $this->getUser();
        $form = $this->createForm(SearchTitleType::class);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $title = $form->getData()['search_title'];
            return $this->redirectToRoute('app_decision_search', ['title' => $title]);
        }

        return $this->render(
            'home/index.html.twig',
            [
                'myLastCurrentDecisions' => $decisionRepository->findByStatus(Decision::STATUS_CURRENT, 3, $user->getId()),
                'myLastDraftDecisions' => $decisionRepository->findByStatus(Decision::STATUS_DRAFT, 3, $user->getId()),
                'myLastFirstDecisions' => $decisionRepository->findByStatus(Decision::STATUS_FIRST_DECISION, 3, $user->getId()),
                'myLastConflictDecisions' => $decisionRepository->findByStatus(Decision::STATUS_CONFLICT, 3, $user->getId()),
                'myLastDoneDecisions' => $decisionRepository->findByStatus(Decision::STATUS_DONE, 3, $user->getId()),
                'myLastUndoneDecisions' => $decisionRepository->findByStatus(Decision::STATUS_UNDONE, 3, $user->getId()),
                'allLastCurrentDecisions' => $decisionRepository->findByStatus(Decision::STATUS_CURRENT, 3),
                'allLastDoneDecisions' => $decisionRepository->findByStatus(Decision::STATUS_DONE, 3),
                'allLastUndoneDecisions' => $decisionRepository->findByStatus(Decision::STATUS_UNDONE, 3),

                'form' => $form->createView()
            ]
        );
    }
}
