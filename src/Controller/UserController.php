<?php

namespace App\Controller;

use App\Entity\Decision;
use App\Entity\User;
use App\Repository\DecisionRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/user', name: 'user_')]
class UserController extends AbstractController
{
    #[Route('/{user}', methods: ['GET'], name: 'show')]
    public function show(User $user, DecisionRepository $decisionRepository): Response
    {

        return $this->render('user/show.html.twig', ['user' => $user, 'decisions' => $decisionRepository->findBy(
            ['owner' => $user],
            ['createdAt' => 'DESC']
        )]);
    }

    #[Route('/{user}/{decision}', methods: ['GET'], name: 'decision')]
    public function oneDecision(User $user, Decision $decision): Response
    {
        // $decision = $decisionRepository->findOneById($id);

        return $this->render('user/decision.html.twig', [ 'user' => $user, 'decision' => $decision ]);
    }
}
