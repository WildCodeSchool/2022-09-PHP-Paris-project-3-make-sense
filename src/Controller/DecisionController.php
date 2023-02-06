<?php

namespace App\Controller;

use DateTime;
use DateInterval;
use App\Entity\Opinion;
use App\Entity\Decision;
use App\Form\OpinionType;
use App\Service\Workflow;
use App\Entity\Validation;
use App\Form\DecisionType;
use App\Form\ValidationType;
use App\Form\FirstDecisionType;
use App\Form\SearchDecisionsType;
use App\Repository\OpinionRepository;
use App\Repository\DecisionRepository;
use App\Repository\ValidationRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\ClickableInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Entity;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/decision', name: 'app_decision_')]

class DecisionController extends AbstractController
{
    private Workflow $workflow;

    public function __construct(Workflow $workflow)
    {
        $this->workflow = $workflow;
    }

    #[Route('/decision/{decision_id}/opinions/{opinionState}', name: 'give_opinion')]
    #[Entity('decision', options: ['mapping' => ['decision_id' => 'id']])]
    public function giveOpinion(
        Decision $decision,
        string $opinionState,
        DecisionRepository $decisionRepository,
        // OpinionLike $opinionLike,
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

            return $this->redirectToRoute('app_decision_show', ['decision_id' => $decision->getId()]);
        }

        return $this->renderForm(
            'opinion/index.html.twig',
            [
                'form' => $form,
                'decision' => $decisionRepository->findOneBy(['id' => $decision->getId()]),
                'opinion' => $opinion,
                // 'opinionLike' => $opinionLike->calculateOpinion($decision),
            ]
        );
    }

    #[Route('/show/{decision_id}', name: 'show')]
    #[Entity('decision', options: ['mapping' => ['decision_id' => 'id']])]
    public function show(Decision $decision): Response
    {
        return $this->render('decision/show.html.twig', [
            'decision' => $decision,
            // 'opinionLike' => $opinionLike->calculateOpinion($decision)
        ]);
    }

    #[Route('/search/{title?}', name: 'search')]
    public function search(
        DecisionRepository $decisionRepository,
        Request $request,
        ?string $title,
        PaginatorInterface $paginator
    ): Response {

        if (!empty($request->request->all())) {
            // dd($request->request->all());
            $title = $request->request->all()['search_decisions']['search'];
        }
        $form = $this->createForm(SearchDecisionsType::class, ['title' => $title]);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $decisions =  $paginator->paginate($decisionRepository->search(
                $form->getData()['search'],
                $form->getData()['status'],
                $form->getData()['departements']
            ), $request->query->getInt('page', 1), 6);
        } else {
            $decisions = $paginator->paginate(
                $decisionRepository->search($title, Decision::STATUS_ALL),
                $request->query->getInt(
                    'page',
                    1
                ),
                6
            );
        }
        return $this->render('decision/search.html.twig', [
            'decisions' => $decisions,
            'form' => $form->createView(),
            'title' => $title
        ]);
    }

    #[Route('/decision/{decision_id}/firstdecision', name: 'firstdecision')]
    #[Entity('decision', options: ['mapping' => ['decision_id' => 'id']])]
    public function firtDecision(
        Decision $decision,
        DecisionRepository $decisionRepository,
        // OpinionLike $opinionLike,
        Request $request
    ): Response {

        $decisionLike = $decisionRepository->findFirstDecisionLike($decision->getId());

        $conflict = 0;
        if ($decisionLike['countLike'] > 0) {
            $conflict = (($decisionLike['sumLike'] / $decisionLike['countLike']) * 100)
                < $decisionLike['likeThreshold'];
        }

        if ($conflict) {
            $decision->setStatus(Decision::STATUS_CONFLICT);
            $date = new DateTime('now');
            $date->add(new DateInterval('P1M'));
            $decision->setEndAt($date);
        } else {
            $decision->setStatus(Decision::STATUS_DONE);
            $date = new DateTime('now');
            $decision->setEndAt($date);
        }

        $form = $this->createForm(FirstDecisionType::class, $decision);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $decisionRepository->save($decision, true);
            $this->workflow->addHistory($decision);
            $this->workflow->addNotifications($decision);

            return $this->redirectToRoute('app_decision_show', ['decision_id' => $decision->getId()]);
        }

        return $this->renderForm(
            'firstdecision/index.html.twig',
            [
                'form' => $form,
                'decision' => $decision,
                // 'opinionLike' => $opinionLike->calculateOpinion($decision)
            ]
        );
    }

    #[Route('/new', name: 'new', methods: ['GET', 'POST'])]
    public function new(
        Request $request,
        DecisionRepository $decisionRepository,
    ): Response {
        $decision = new Decision();
        /** @var \App\Entity\User */
        $user = $this->getUser();

        $decision->setOwner($user);
        $form = $this->createForm(DecisionType::class, $decision);
        $form->handleRequest($request);
        $errors = [];
        if ($form->isSubmitted() && $form->isValid()) {
            /** @var ClickableInterface $saveAsDraft  */
            $saveAsDraft = $form->get('saveAsDraft');
            if ($saveAsDraft->isClicked()) {
                $decision->setStatus(Decision::STATUS_DRAFT);
                $decisionRepository->save($decision, true);
                $this->workflow->addHistory($decision);
            }
            /** @var ClickableInterface $save  */
            $save = $form->get('save');
            if ($save->isClicked()) {
                $decision->setStatus(Decision::STATUS_CURRENT);
                $decisionRepository->save($decision, true);
                $this->workflow->addHistory($decision);
                $this->workflow->addNotifications($decision);
            }

            // $this->workflow->addHistory($decision);
            // $decisionRepository->save($decision, true);
            $this->addFlash('success', 'Decision sucessfully created !');
            return $this->redirectToRoute('app_dashboard');
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


    #[Route('/edit/{decision_id}', name: 'edit', methods: ['GET', 'POST'])]
    #[Entity('decision', options: ['mapping' => ['decision_id' => 'id']])]
    public function edit(
        Decision $decision,
        Request $request,
        DecisionRepository $decisionRepository,
    ): Response {

        // $decision = new Decision();
        /** @var \App\Entity\User */
        $user = $this->getUser();

        // $user = $userRepository->findOneById([HomeController::USERID]);
        $decision->setOwner($user);
        $form = $this->createForm(DecisionType::class, $decision);
        $form->handleRequest($request);
        $errors = [];
        if ($form->isSubmitted() && $form->isValid()) {
            /** @var ClickableInterface $saveAsDraft  */
            $saveAsDraft = $form->get('saveAsDraft');
            if ($saveAsDraft->isClicked()) {
                $decision->setUpdatedAt(new DateTime('now'));
                $decision->setStatus(Decision::STATUS_DRAFT);
            }
            /** @var ClickableInterface $save  */
            $save = $form->get('save');
            if ($save->isClicked()) {
                $decision->setUpdatedAt(new DateTime('now'));
                $decision->setStatus(Decision::STATUS_CURRENT);
            }

            $this->workflow->addHistory($decision);
            $decisionRepository->save($decision, true);
            $this->addFlash('success', 'Decision sucessfully created !');
            return $this->redirectToRoute('app_dashboard');
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


    #[Route('/validation/{decision_id}', name: 'validation')]
    #[Entity('decision', options: ['mapping' => ['decision_id' => 'id']])]
    public function index(
        Decision $decision,
        // OpinionLike $opinionLike,
        ValidationRepository $validationRepository,
        Request $request
    ): Response {

        /** @var \App\Entity\User */
        $user = $this->getUser();

        $validation = $validationRepository->findOneBy(
            [
                'user' => $user->getId(),
                'decision' => $decision->getId()
            ]
        );

        if (!$validation) {
            $validation = new Validation();
            $validation->setDecision($decision);
            $validation->setUser($user);
        }

        $form = $this->createForm(ValidationType::class, $validation);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            /** @var ClickableInterface $button  */
            $button = $form->get('avispositif');
            $button->isClicked() ? $validation->setIsApproved(true) : $validation->setIsApproved(false);

            $validationRepository->save($validation, true);

            return $this->redirectToRoute('app_decision_show', ['decision_id' => $decision->getId()]);
        }

        return $this->renderForm(
            'validation/index.html.twig',
            [
                'form' => $form,
                'decision' => $decision,
                'validation' => $validation,
                // 'opinionLike' => $opinionLike->calculateOpinion($decision),
            ]
        );
    }
}
