<?php

namespace App\Controller;

use App\Entity\Decision;
use App\Form\SearchTitleType;
use PhpParser\Builder\Method;
use App\Form\SearchDecisionsType;
use App\Controller\HomeController;
use Composer\XdebugHandler\Status;
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

#[Route('/decision', name: 'decision_')]
class DecisionController extends AbstractController
{
    #[Route('/{title?}', name: 'search')]
    public function search(DecisionRepository $decisionRepository, Request $request, ?string $title): Response
    {
        if (!empty($request->request->all())) {
            $title = $request->request->all()['search_decisions']['search'];
        }
        $form = $this->createForm(SearchDecisionsType::class, ['title' => $title]);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $decisions = $decisionRepository->search(
                $form->getData()['search'],
                $form->getData()['status'],
                $form->getData()['departements']
            );
        } else {
            $decisions = $decisionRepository->search($title, Decision::STATUS_ALL);
        }
        return $this->render('decision/index.html.twig', [
            'decisions' => $decisions,
            'form' => $form->createView(),
            'title' => $title
        ]);
    }

    #[Route('/decision/{decisionId}/opinions/{opinionState}', name: 'app_opinion')]
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
