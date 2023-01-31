<?php

namespace App\Controller;

use App\Entity\Opinion;
use App\Entity\Decision;
use App\Form\OpinionType;
use App\Service\Workflow;
use App\Service\OpinionLike;
use App\Form\FirstDecisionType;
use App\Repository\OpinionRepository;
use App\Repository\DecisionRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Entity;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class DecisionController extends AbstractController
{
    private Workflow $workflow;

    public function __construct(Workflow $workflow)
    {
        $this->workflow = $workflow;
    }

    #[Route('/decision/{decision_id}/opinions/{opinionState}', name: 'app_give_opinion')]
    #[Entity('decision', options: ['mapping' => ['decision_id' => 'id']])]
    public function giveOpinion(
        Decision $decision,
        string $opinionState,
        DecisionRepository $decisionRepository,
        OpinionLike $opinionLike,
        OpinionRepository $opinionRepository,
        Request $request
    ): Response {

        /** @var \App\Entity\User */
        $user = $this->getUser();

        $opinion = $opinionRepository->findOneBy(['user' => $user->getId(), 'decision' => $decision->getId()]);

        if (!$opinion) {
            $opinion = new Opinion();
            $opinion->setDecision($decision);
            $opinion->setUser($user);
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
            ]
        );
    }

    #[Route('/decision/firstdecision/{decision_id}', name: 'app_first_decision')]
    #[Entity('decision', options: ['mapping' => ['decision_id' => 'id']])]
    public function firtDecision(
        Decision $decision,
        DecisionRepository $decisionRepository,
        OpinionLike $opinionLike,
        Request $request
    ): Response {

        $decisionLike = $decisionRepository->findFirstDecisionLike($decision->getId());

        $conflict = (($decisionLike['sumLike'] / $decisionLike['countLike']) * 100)
            < $decisionLike['likeThreshold'];

        if ($conflict) {
            $decision->setStatus(Decision::STATUS_CONFLICT);
        } else {
            $decision->setStatus(Decision::STATUS_DONE);
        }

        $form = $this->createForm(FirstDecisionType::class, $decision);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $decisionRepository->save($decision, true);
            $this->workflow->addHistory($decision);
            $this->workflow->addNotifications($decision);

            return $this->redirectToRoute('app_home');
        }

        return $this->renderForm(
            'firstdecision/index.html.twig',
            [
                'form' => $form,
                'decision' => $decision,
                'opinionLike' => $opinionLike->calculateOpinion($decision)
            ]
        );
    }
}
