<?php

namespace App\Controller;

use App\Entity\Decision;
use App\Entity\Department;
use App\Entity\User;
use App\Repository\DecisionRepository;
use App\Repository\DepartmentRepository;
use MercurySeries\FlashyBundle\FlashyNotifier;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Entity;
use App\Form\DecisionType;
use App\Repository\UserRepository;
use Symfony\Component\Form\ClickableInterface;
use App\Service\Workflow;

#[Route('/decision', name: 'decision_')]
class DecisionController extends AbstractController
{
    /**
     * @var Workflow
     */
    private $workflow;

    public function __construct(Workflow $workflow)
    {
        $this->workflow = $workflow;
    }
    #[Route('/new', name: 'new', methods: ['GET', 'POST'])]
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
        }else{
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
