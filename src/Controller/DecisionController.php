<?php

namespace App\Controller;

use App\Service\OpinionLike;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Decision;

#[Route('/decision', methods: ['GET'], name: 'decision_')]
class DecisionController extends AbstractController
{
    #[Route('/{decision}', name: 'show')]
    public function show(Decision $decision, OpinionLike $opinionLike): Response
    {
        return $this->render('decision/decision.html.twig', [
            'decision' => $decision, 'opinionLike' => $opinionLike->calculateOpinion($decision)
        ]);
    }
}
