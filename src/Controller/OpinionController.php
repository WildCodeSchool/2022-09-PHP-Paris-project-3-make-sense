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

class OpinionController extends AbstractController
{
    #[Route('/opinion/{decision_id}/{opinionState}', name: 'app_opinion')]
    #[Entity('decision', options: ['mapping' => ['decision_id' => 'id']])]
    public function index(
        Decision $decision,
        string $opinionState,
        DecisionRepository $decisionRepository,
        OpinionLike $opinionLike,
        UserRepository $userRepository,
        OpinionRepository $opinionRepository,
        Request $request
    ): Response {

        $userId = 54;

        $opinion = $opinionRepository->findOneBy(['user' => $userId, 'decision' => $decision->getId()]);

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
