<?php

namespace App\Controller;

use App\Entity\Decision;
use App\Entity\Department;
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
use Symfony\Component\Form\ClickableInterface;

class DecisionController extends AbstractController
{
    #[Route('/decision', name: 'app_decision')]
    public function index(DecisionRepository $decisionRepository): Response
    {
        $decisions = $decisionRepository->findDecisionDepartment();
        return $this->render('decision/index.html.twig', [
            'decisions' => $decisions,
        ]);
    }

    #[Route('/decision/new', name: 'decision_new', methods: ['GET', 'POST'])]
    public function new(Request $request, DecisionRepository $decisionRepository): Response
    {
        $decision = new Decision();
        $form = $this->createForm(DecisionType::class, $decision);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
             /** @var ClickableInterface $button  */
             $button = $form->get('status');
             $button->isClicked();
            if ($button->isClicked()){
                $decision->setStatus('brouillon');
            }
            /** @var ClickableInterface $btn  */
            $btn = $form->get('submit');
            $btn->isClicked();
            if ($btn->isClicked()){
                $decision->setStatus('en cours');
            }
            $decisionRepository->save($decision, true);
            $this->addFlash('success', 'Decision sucessfully created !');
            return $this->redirectToRoute('app_decision');
        }
        return $this->renderForm('decision/new.html.twig', [
            'form' => $form,
        ]);
    }
}