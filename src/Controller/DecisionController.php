<?php

namespace App\Controller;

use App\Entity\Decision;
use App\Repository\DecisionRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Entity;
use Symfony\Component\Form\ClickableInterface;
use App\Service\Workflow;
use App\Form\FirstDecisionType;
use App\Form\DecisionType;
use App\Service\OpinionLike;
use App\Repository\UserRepository;

#[Route('/decision', name: 'decision_')]

class DecisionController extends AbstractController
{
    private Workflow $workflow;

    public function __construct(Workflow $workflow)
    {
        $this->workflow = $workflow;
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
            'conflict/index.html.twig',
            [
                'form' => $form,
                'decision' => $decision,
                'opinionLike' => $opinionLike->calculateOpinion($decision),
                'user' => $userRepository->findOneBy(['id' => $decision->getOwner()])
            ]
        );
    }

    #[Route('/new', name: 'decision_new', methods: ['GET', 'POST'])]
    public function new(
        Request $request,
        DecisionRepository $decisionRepository,
        UserRepository $userRepository
    ): Response {
        $decision = new Decision();
        $user = $userRepository->findOneById([HomeController::USERID]);
        $decision->setOwner($user);
        $form = $this->createForm(DecisionType::class, $decision);
        $form->handleRequest($request);
        $errors = [];
if ($form->isSubmitted() && $form->isValid()) {
    /** @var ClickableInterface $saveAsDraft  */
    $saveAsDraft = $form->get('saveAsDraft');
    if ($saveAsDraft->isClicked()) {
        $decision->setStatus(Decision::STATUS_DRAFT);
    }
    /** @var ClickableInterface $save  */
    $save = $form->get('save');
    if ($save->isClicked()) {
        $decision->setStatus(Decision::STATUS_CURRENT);
    }
    $this->workflow->addHistory($decision);
    $decisionRepository->save($decision, true);
    $this->addFlash('success', 'Decision sucessfully created !');
    return $this->redirectToRoute('index_show');

        } else {
            foreach ($form->getErrors(true) as $error) {
                $errors[] = $error->getMessage();
            }
        }
        return $this->renderForm('decision/new.html.twig', [
            'form' => $form,
            'errors' => $errors,
        ]);
    }
}
