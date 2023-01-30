<?php

namespace App\Controller;

use App\Entity\Decision;
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
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Entity;

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

    #[Route('/{decisionId}/opinions/{opinionState}', name: 'app_opinion')]
    #[Entity('decision', options: ['mapping' => ['decisionId' => 'id']])]
    public function giveOpinion(
        Decision $decision,
        string $opinionState,
        DecisionRepository $decisionRepository,
        OpinionLike $opinionLike,
        UserRepository $userRepository,
        OpinionRepository $opinionRepository,
        Request $request
    ): Response {

        $opinion = $opinionRepository->findOneBy(['user' => HomeController::USERID, 'decision' => $decision->getId()]);

        if (!$opinion) {
            $opinion = new Opinion();
            $opinion->setDecision($decision);
            $opinion->setUser($userRepository->findOneBy(['id' => HomeController::USERID]));
            if ($opinionState == "like") {
                $opinion->setIsLike(true);
            } else {
                $opinion->setIsLike(false);
            }
        }

        $form = $this->createForm(OpinionType::class, $opinion);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $opinionRepository->save($opinion, true);

            return $this->redirectToRoute('app_home');
        }

        return $this->renderForm(
            'opinion/index.html.twig',
            [
                'form' => $form,
                'decision' => $decisionRepository->findOneBy(['id' => $decision->getId()]),
                'opinion' => $opinion,
                'opinionLike' => $opinionLike->calculateOpinion($decision),
                'user' => $userRepository->findOneBy(['id' => $decision->getOwner()])
            ]
        );
    }
}
