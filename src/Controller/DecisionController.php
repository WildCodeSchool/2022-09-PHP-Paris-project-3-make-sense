<?php

namespace App\Controller;

use App\Entity\Opinion;
use App\Entity\Decision;
use App\Form\OpinionType;
use App\Service\Workflow;
use App\Service\OpinionLike;
use App\Form\SearchTitleType;
use PhpParser\Builder\Method;
use App\Form\FirstDecisionType;
use App\Form\SearchDecisionsType;
use App\Controller\HomeController;
use App\Repository\UserRepository;
use Composer\XdebugHandler\Status;
use App\Repository\DecisionRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Entity;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\ClickableInterface;
use App\Form\DecisionType;

#[Route('/decision', name: 'decision_')]

class DecisionController extends AbstractController
{
    private Workflow $workflow;

    public function __construct(Workflow $workflow)
    {
        $this->workflow = $workflow;
    }

    #[Route('/show/{decision}', name: 'show')]
    public function show(Decision $decision, OpinionLike $opinionLike): Response
    {
        return $this->render('decision/decision.html.twig', [
            'decision' => $decision, 'opinionLike' => $opinionLike->calculateOpinion($decision)
        ]);
    }

    #[Route('/{title?}', name: 'search')]
    public function search(
        DecisionRepository $decisionRepository,
        Request $request,
        ?string $title,
        PaginatorInterface $paginator
    ): Response {
        if (!empty($request->request->all())) {
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
        return $this->render('decision/index.html.twig', [
            'decisions' => $decisions,
            'form' => $form->createView(),
            'title' => $title
        ]);
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
