<?php

namespace App\Controller;

use App\Entity\Decision;
use App\Entity\User;
use App\Repository\DecisionRepository;
use App\Service\OpinionLike;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
#[Route('/user', name: 'index')]
class UserController extends AbstractController
{
    #[Route('/{user}', methods: ['GET'], name: 'show')]
    public function show(User $user, DecisionRepository $decisionRepository, OpinionLike $opinionLike): Response
    {

        $decisions = $decisionRepository->findBy(
            ['owner' => $user],
            ['createdAt' => 'DESC']);

        $opinionLikes = $opinionLike->calculateAllOpinion($decisions);
        
        return $this->render('user/show.html.twig', ['user' => $user, 'decisions'=>$decisions, 'opinionLikes'=>$opinionLikes]);
    }
}