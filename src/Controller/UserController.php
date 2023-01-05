<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use App\Repository\DepartmentRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;


class UserController extends AbstractController
{
    #[Route('/new', name: 'user_new', methods: ['POST' , 'GET'])]
    public function new( Request $request, EntityManagerInterface $manager, DepartmentRepository $departmentRepository): Response
    {
        $user = new User();
        $form = $this->createForm(UserType::class, $user);
        $expertises = $departmentRepository->findAllExpertiseByDepartement();
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()){
             $user = $form->getData();

             $manager->persist($user);
             $manager->flush();

            $this->redirectToRoute('security.login');
        }
        return $this->render('user/new.html.twig', [
            'form' => $form->createView(),
             'expertises' => $expertises,
        ]);
    }
}
