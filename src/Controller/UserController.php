<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\DecisionRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/user', name: 'app_user_')]
class UserController extends AbstractController
{
    #[Route('/show-decisions/{id}', methods: ['GET'], name: 'show')]
    public function show(User $user, DecisionRepository $decisionRepository): Response
    {
        return $this->render(
            'user/show.html.twig',
            [
                'user' => $user,
                'decisions' => $decisionRepository->findBy(['owner' => $user], ['createdAt' => 'DESC'])
            ]
        );
    }
}
