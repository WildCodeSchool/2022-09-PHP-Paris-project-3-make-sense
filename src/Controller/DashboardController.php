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
    // public const USERID = 41;

    #[Route('/dashboard', name: 'app_dashboard')]
    public function index(
        DecisionRepository $decisionRepository,
        OpinionLike $opinionLike,
        Request $request
    ): Response {

        /** @var \App\Entity\User */
        $user = $this->getUser();
        $form = $this->createForm(SearchTitleType::class);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $title = $form->getData()['search_title'];
            return $this->redirectToRoute('decision_search', ['title' => $title]);
        }
        $myLastDecisions = $decisionRepository->findByStatus(Decision::STATUS_CURRENT, 3, $user->getId());
        $allLastDecisions = $decisionRepository->findByStatus(Decision::STATUS_CURRENT, 3);
        $myLastDrafts = $decisionRepository->findByStatus(Decision::STATUS_DRAFT, 3, $user->getId());
        $allLastAccomplished = $decisionRepository->findByStatus(Decision::STATUS_DONE, 3);

        return $this->render(
            'home/index.html.twig',
            [
                'myLastDecisions' => $myLastDecisions,
                'myLastDecisionsOpinion' => $opinionLike->calculateAllOpinion($myLastDecisions),

                'allLastDecisions' => $allLastDecisions,
                'allLastDecisionsOpinion' => $opinionLike->calculateAllOpinion($allLastDecisions),

                'myLastDrafts' => $myLastDrafts,
                'myLastDraftsOpinion' => $opinionLike->calculateAllOpinion($myLastDrafts),

                'allLastAccomplished' => $allLastAccomplished,
                'allLastAccomplishedOpinion' => $opinionLike->calculateAllOpinion($allLastAccomplished),

                'form' => $form->createView()
            ]
        );
    }
}
