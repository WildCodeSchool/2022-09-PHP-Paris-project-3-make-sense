<?php

namespace App\Controller;

use App\Service\OpinionLike;
use App\Repository\DecisionRepository;
use App\Repository\HistoryRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    #[Route('/', name: 'app_home')]
    public function index(
        DecisionRepository $decisionRepository,
        HistoryRepository $historyRepository,
        OpinionLike $opinionLike
    ): Response {

        $owner = 202;

        $myLastDecisions = $decisionRepository->findBy(['owner' => $owner], ['createdAt' => 'DESC'], 3, 0);


        $allLastDecisions = $decisionRepository->findBy([], ['createdAt' => 'DESC'], 3, 0);


        $myLastDrafts = $decisionRepository->findByHistory(
            $historyRepository->findLastUpdatedByStatus("Brouillon", 3),
            $owner
        );


        $allLastAccomplished = $decisionRepository->findByHistory(
            $historyRepository->findLastUpdatedByStatus("Aboutie", 3)
        );



        return $this->render(
            'home/index.html.twig',
            [
                'myLastDecisions' => $myLastDecisions,
                'myLastDecisionsOpinion' => $opinionLike->calculateAllOpinion($myLastDecisions),

                'allLastDecisions' => $allLastDecisions,
                'allLastDecisionsOpinion' => $opinionLike->calculateAllOpinion($allLastDecisions),

                'myLastDrafts' => $myLastDrafts,
                'myLastDraftsOpinion' => $opinionLike->calculateAllOpinion($myLastDrafts),

                'AllLastAccomplished' => $allLastAccomplished,
                'AllLastAccomplishedOpinion' => $opinionLike->calculateAllOpinion($allLastAccomplished)
            ]
        );
    }
}
