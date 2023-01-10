<?php

namespace App\Controller;

use App\Entity\Decision;
use App\Entity\Validation;
use App\Form\ValidationType;
use App\Service\OpinionLike;
use App\Repository\UserRepository;
use App\Repository\DecisionRepository;
use App\Repository\ValidationRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Entity;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Controller\ClickableInterface;

class ValidationController extends AbstractController
{
    #[Route('/validation/{decision_id}', name: 'app_opinion')]
    #[Entity('decision', options: ['mapping' => ['decision_id' => 'id']])]
    public function index(
        Decision $decision,
        DecisionRepository $decisionRepository,
        OpinionLike $opinionLike,
        UserRepository $userRepository,
        ValidationRepository $validationRepository,
        Request $request
    ): Response {

        $userId = 202;

        $validation = $validationRepository->findOneBy(['user' => $userId, 'decision' => $decision->getId()]);

        if (!$validation) {
            $validation = new Validation();
            $validation->setDecision($decision);
            $validation->setUser($userRepository->findOneBy(['id' => $userId]));
        }

        $form = $this->createForm(ValidationType::class, $validation);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // dd($form->getData());
            /** @var ClickableInterface $button  */
            $button = $form->get('avispositif');
            $button->isClicked() ? $validation->setIsApproved(true) : $validation->setIsApproved(false);

            $validationRepository->save($validation, true);

            // completer la route vers decision_dashboard
            return $this->redirectToRoute('/');
        }

    
        return $this->renderForm(
            'validation/index.html.twig',
            [
                'form' => $form,
                'decision' => $decisionRepository->findLastStatus($decision->getId()),
                'validation' => $validation,
                'opinionLike' => $opinionLike->calculateOpinion($decision),
                'user' => $userRepository->findOneBy(['id' => $decision->getOwner()])
            ]
        );
    }
}
