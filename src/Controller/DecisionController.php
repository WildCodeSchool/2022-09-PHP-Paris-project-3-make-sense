<?php

namespace App\Controller;

use App\Entity\Decision;
use App\Repository\DecisionRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Entity;
use App\Form\DecisionType;

class DecisionController extends AbstractController
{
    #[Route('/decision', name: 'app_decision')]
    public function index(DecisionRepository $decisionRepository): Response
    {
        $decisions = $decisionRepository->findAll();
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
            $decisionRepository->save($decision, true);          
            return $this->redirectToRoute('app_home');
        }
        return $this->renderForm('decision/new.html.twig', [
            'form' => $form,
        ]);
    }
}
