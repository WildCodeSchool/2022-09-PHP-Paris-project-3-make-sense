<?php

namespace App\Controller;

use App\Entity\Opinion;
use App\Form\OpinionType;
use App\Service\OpinionLike;
use App\Repository\UserRepository;
// use App\Repository\OpinionRepository;
use App\Repository\DecisionRepository;
use Symfony\Component\HttpFoundation\Request;
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
        UserRepository $userRepository,
        // OpinionRepository $opinionRepository,
        Request $request
    ): Response {

        // dd($opinion);
        $decision = $decisionRepository->findOneBy(['id' => $decisionId]);
        // dd($decisionRepository->findLastStatus($decisionId));
        // $owner = 202;

        $opinion = new Opinion();

        $form = $this->createForm(OpinionType::class, $opinion);
       
        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            
        
            return $this->redirectToRoute('app_index');
        }

        
        return $this->renderForm(
            'opinion/index.html.twig',
            [
                'form' => $form,
                'decision' => $decisionRepository->findLastStatus($decisionId),
                'decisionOpinion' => $opinion,
                'opinionLike' => $opinionLike->calculateOpinion($decision),
                'user' => $userRepository->findOneBy(['id' => $decision->getOwner()])
            ]
        );
    }
}
