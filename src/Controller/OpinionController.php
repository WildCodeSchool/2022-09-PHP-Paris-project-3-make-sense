<?php

namespace App\Controller;

use App\Service\OpinionLike;
use App\Repository\UserRepository;
use App\Repository\DecisionRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class OpinionController extends AbstractController
{
    #[Route('/opinion/{decisionId}/{opinion}', name: 'app_opinion')]
    public function index(
        int $decisionId,
        string $opinion,
        DecisionRepository $decisionRepository,
        OpinionLike $opinionLike,
        UserRepository $userRepository
    ): Response {

        // dd($opinion);
        $decision = $decisionRepository->findOneBy(['id' => $decisionId]);
        // dd($decisionRepository->findLastStatus($decisionId));
        // $owner = 202;

        return $this->render(
            'opinion/index.html.twig',
            [
                'decision' => $decisionRepository->findLastStatus($decisionId),
                'decisionOpinion' => $opinion,
                'opinionLike' => $opinionLike->calculateOpinion($decision),
                'user' => $userRepository->findOneBy(['id' => $decision->getOwner()])
            ]
        );
    }
}
