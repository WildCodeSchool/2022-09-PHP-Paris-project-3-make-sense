<?php

namespace App\Controller;

use App\Entity\Decision;
use App\Entity\Validation;
use App\Form\ValidationType;
use App\Service\OpinionLike;
use App\Repository\UserRepository;
use App\Repository\ValidationRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\ClickableInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Entity;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ValidationController extends AbstractController
{
    #[Route('/validation/{decision_id}', name: 'app_opinion')]
    #[Entity('decision', options: ['mapping' => ['decision_id' => 'id']])]
    public function index(
        Decision $decision,
        OpinionLike $opinionLike,
        UserRepository $userRepository,
        ValidationRepository $validationRepository,
        Request $request
    ): Response {

        $validation = $validationRepository->findOneBy(
            [
                'user' => HomeController::USERID,
                'decision' => $decision->getId()
            ]
        );

        if (!$validation) {
            $validation = new Validation();
            $validation->setDecision($decision);
            $validation->setUser($userRepository->findOneBy(['id' =>  HomeController::USERID]));
        }

        $form = $this->createForm(ValidationType::class, $validation);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            /** @var ClickableInterface $button  */
            // dd($request->getRequest());
            $button = $form->get('avispositif');
            $button->isClicked() ? $validation->setIsApproved(true) : $validation->setIsApproved(false);

            $validationRepository->save($validation, true);

            return $this->redirectToRoute('app_home');
        }

        return $this->renderForm(
            'validation/index.html.twig',
            [
                'form' => $form,
                'decision' => $decision,
                'validation' => $validation,
                'opinionLike' => $opinionLike->calculateOpinion($decision),
                'user' => $userRepository->findOneBy(['id' => $decision->getOwner()])
            ]
        );
    }
}
