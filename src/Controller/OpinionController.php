<?php

namespace App\Controller;

use App\Entity\Opinion;
use App\Form\OpinionType;
use App\Service\OpinionLike;
use App\Repository\UserRepository;
use App\Repository\OpinionRepository;
use App\Repository\DecisionRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class OpinionController extends AbstractController
{
    #[Route('/opinion/{decisionId}/{opinionState}', name: 'app_opinion')]
    public function index(
        int $decisionId,
        string $opinionState,
        DecisionRepository $decisionRepository,
        OpinionLike $opinionLike,
        UserRepository $userRepository,
        OpinionRepository $opinionRepository,
        Request $request
    ): Response {

        $decision = $decisionRepository->findOneBy(['id' => $decisionId]);

        $userId = 202;

        // $opinion = new Opinion();

        $opinion = $opinionRepository->findOneBy(['user' => $userId, 'decision' => $decisionId]);

        if (!$opinion) {
            $opinion = new Opinion();
            $opinion->setDecision($decision);
            $opinion->setUser($userRepository->findOneBy(['id' => $userId]));

            if ($opinionState == "like") {
                $opinion->setIsLike(true);
            } else {
                $opinion->setIsLike(false);
            }
        }

        // if ($opinion_ == "like") {
        //     $opinion->setIsLike(true);
        // } else {
        //     $opinion->setIsLike(false);
        // }

        $form = $this->createForm(OpinionType::class, $opinion);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // dd($form->getData());
            $opinionRepository->save($opinion, true);

            // completer la route vers decision_dashboard
            return $this->redirectToRoute('/');
        }

        return $this->renderForm(
            'opinion/index.html.twig',
            [
                'form' => $form,
                'decision' => $decisionRepository->findLastStatus($decisionId),
                'opinion' => $opinion,
                'opinionLike' => $opinionLike->calculateOpinion($decision),
                'user' => $userRepository->findOneBy(['id' => $decision->getOwner()])
            ]
        );
    }
}
