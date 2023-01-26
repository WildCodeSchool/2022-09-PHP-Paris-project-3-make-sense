<?php

namespace App\Controller;

use App\Service\UpdateHistory;
use App\Entity\Decision;
use App\Form\FirstDecisionType;
use App\Service\OpinionLike;
use App\Repository\UserRepository;
use App\Repository\DecisionRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Entity;

class DecisionController extends AbstractController
{
    private UpdateHistory $updateHistory;

    public function __construct(UpdateHistory $updateHistory)
    {
        $this->updateHistory = $updateHistory;
    }

    #[Route('/decision/{decisionId}/firstdecision', name: 'app_conflict')]
    #[Entity('decision', options: ['mapping' => ['decisionId' => 'id']])]
    public function firtDecision(
        Decision $decision,
        DecisionRepository $decisionRepository,
        OpinionLike $opinionLike,
        UserRepository $userRepository,
        Request $request
    ): Response {

        $decisionLike = $decisionRepository->findFirstDecisionLike($decision->getId());

        $conflict = (($decisionLike['sumLike'] / $decisionLike['countLike']) * 100)
            < $decisionLike['likeThreshold'];

        $form = $this->createForm(FirstDecisionType::class, $decision, ['attr' => ['conflict' => $conflict]]);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if ($conflict) {
                $decision->setStatus(Decision::STATUS_CONFLICT);
            } else {
                $decision->setStatus(Decision::STATUS_DONE);
            }

            $this->updateHistory->update($decision);
            $decisionRepository->save($decision, true);

            return $this->redirectToRoute('app_home');
        }

        return $this->renderForm(
            'conflict/index.html.twig',
            [
                'form' => $form,
                'decision' => $decision,
                'conflict' => ((($decisionLike['sumLike'] / $decisionLike['countLike']) * 100)
                    < $decisionLike['likeThreshold']),
                'opinionLike' => $opinionLike->calculateOpinion($decision),
                'user' => $userRepository->findOneBy(['id' => $decision->getOwner()])
            ]
        );
    }
}
