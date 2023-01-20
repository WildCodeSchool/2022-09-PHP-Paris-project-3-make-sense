<?php

namespace App\Controller;

use App\Entity\Decision;
use App\Service\OpinionLike;
use App\Repository\DecisionRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    #[Route('/', name: 'app_home')]
    public function index(
        DecisionRepository $decisionRepository,
        OpinionLike $opinionLike
    ): Response {

        $owner = 41;

        $myLastDecisions = $decisionRepository->findByStatus(Decision::STATUS_CURRENT, 3, $owner);
        $allLastDecisions = $decisionRepository->findByStatus(Decision::STATUS_CURRENT, 3);
        $myLastDrafts = $decisionRepository->findByStatus(Decision::STATUS_DRAFT, 3, $owner);
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

                'AllLastAccomplished' => $allLastAccomplished,
                'AllLastAccomplishedOpinion' => $opinionLike->calculateAllOpinion($allLastAccomplished)
            ]
        );
    }
}
