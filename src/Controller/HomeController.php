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

        $owner = 5;

        // dd($decisionRepository->findLastUpdatedByStatus('en cours', 3, $owner));


        // $myLastDecisions = $decisionRepository->findByHistory(
        //     $historyRepository->findLastUpdatedByStatus("En cours", 3),
        //      $owner
        //  );

        $myLastDecisions = $decisionRepository->findLastUpdatedByStatus('en cours', 3, $owner);

        // $allLastDecisions = $decisionRepository->findByHistory(
        //     $historyRepository->findLastUpdatedByStatus("En cours", 3)
        // );

        $allLastDecisions = $decisionRepository->findLastUpdatedByStatus('en cours', 3);

        // $myLastDrafts = $decisionRepository->findByHistory(
        //     $historyRepository->findLastUpdatedByStatus("Brouillon", 3),
        //     $owner
        // );

        $myLastDrafts = $decisionRepository->findLastUpdatedByStatus('brouillon', 3, $owner);
        // $allLastAccomplished = $decisionRepository->findByHistory(
        //     $historyRepository->findLastUpdatedByStatus("Aboutie", 3)
        // );
        $allLastAccomplished = $decisionRepository->findLastUpdatedByStatus('aboutie', 3);

        // // dd($allLastDecisions);
        // dd($opinionLike->calculateAllOpinion($allLastDecisions));

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
