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

#[Route('/decision', name: 'decision_')]
class DecisionController extends AbstractController
{
    public function index(): Response
    {
    }

    #[Route('/new', name: 'new', methods: ['GET', 'POST'])]
    public function new(
        Request $request,
        DecisionRepository $decisionRepository,
        UserRepository $userRepository
    ): Response {
        $decision = new Decision();
        $user = $userRepository->findOneById([HomeController::USERID]);
        dd($userRepository->findOneById([HomeController::USERID]));
        $decision->setOwner($user);
        $form = $this->createForm(DecisionType::class, $decision);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
             /** @var ClickableInterface $button  */
             $button = $form->get('saveAsDraft');
             $button->isClicked();
            if ($button->isClicked()) {
                $decision->setStatus(Decision::STATUS_DRAFT);
            }
            /** @var ClickableInterface $btn  */
            $btn = $form->get('save');
            if ($btn->isClicked()) {
                $decision->setStatus(Decision::STATUS_CURRENT);
            }
            $decisionRepository->save($decision, true);
            $this->addFlash('success', 'Decision sucessfully created !');
            return $this->redirectToRoute('index_show');
        }
        return $this->renderForm('decision/new.html.twig', [
            'form' => $form,
        ]);
    }
}
