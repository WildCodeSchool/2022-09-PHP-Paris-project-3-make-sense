<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use DateTimeImmutable;
use App\Entity\Expertise;
use App\Repository\UserRepository;
use App\Repository\ExpertiseRepository;
use App\Repository\DepartmentRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\BrowserKit\Response as BrowserKitResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class UserController extends AbstractController
{

    public function updateExpertise(
        $form_results, 
        $experts, 
        ExpertiseRepository $expertiseRepository, 
        USER $user, 
        DepartmentRepository $departmentRepository): void
    {
    
        foreach ($experts as $key => $expert) {
            
            if ($expert['exp_id'] == null) {
                // dd($form['expert' . $key]->getData());
                if ($form_results[$expert['dep_name']]) {
                    $expertise = new Expertise();
                    $expertise->SetUser($user);
                    // if ($expert['isExpert'] == null)
                    // dd('1')  ;
                    $expertise->setisExpert($form_results[$expert['dep_name']]  == 'expert' ? true : false);
                    $expertise->setDepartment($departmentRepository->findOneById($expert['dep_id']));
                    // dd($expertise);
                    $expertiseRepository->save($expertise, true);
                }
                else {
                    // a faire
                }
            }
            else {         
                   
                    $expertise = $expertiseRepository->findOneById((int)($expert['exp_id']));
                  
                    if ($form_results[$expert['dep_name']] == 'aucune') {
                
                        $expertiseRepository->remove($expertise, true);
                    
                    }
                    else {
                        $expertise->setisExpert($form_results[$expert['dep_name']] == 'expert' ? true : false );
                       
                        $expertiseRepository->save($expertise, true);
                    }
                }
        }
    }

    #[Route('/user/edit/{id}', name: 'user_edit', methods: ['POST' , 'GET'])]
    public function edit( UserRepository $userRepository, User $user, Request $request, DepartmentRepository $departmentRepository, ExpertiseRepository $expertiseRepository): Response
    {

      $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);
        // $user->setUpdatedAt(new DateTimeImmutable('now'));

             if ($form->isSubmitted() && $form->isValid()) {
            // dd($form->isValid());
            // dd( $expertiseRepository->findOneById(110));
            $form_results = $request->request->all()['user']['departments'];
            $experts = $departmentRepository->findAllExpertiseByDepartement($user->getId());

            $this->updateExpertise($form_results, $experts, $expertiseRepository, $user, $departmentRepository);
            $userRepository->save($user, true);   
            
            //   dd('save done');
            return $this->redirectToRoute('user');
        }

        return $this->render('user/edit.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/user/new', name: 'user_new', methods: ['POST' , 'GET'])]
    public function new(UserRepository $userRepository, Request $request, EntityManagerInterface $manager, DepartmentRepository $departmentRepository, ExpertiseRepository $expertiseRepository): Response
    {
        $user = new User();
        $form = $this->createForm(UserType::class, $user);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()){
            // dd($form->isSubmitted());
            // $form_results = $request->request->all()['user']['departments'];
            //  $experts = $departmentRepository->findAllExpertiseByDepartement($user->getId());

            // $this->updateExpertise($form_results, $experts, $expertiseRepository, $user, $departmentRepository);
            // $userRepository->save($user, true);  
            // dd('save done');
             $user = $form->getData();
             $user->setUpdatedAt(new DateTimeImmutable('now'));
             $manager->persist($user);
             $manager->flush();

           return $this->redirectToRoute('user');
        }

        return $this->render('user/new.html.twig', [
            'form' => $form->createView()
        ]);
    }

    #[Route('/user/delete/{id}', name: 'user_delete', methods: ['GET'])]
    public function delete(EntityManagerInterface $manager, User $user): Response
    {     
        if (!$user){
            return $this->redirect('user.index');
        }
          $manager->remove($user);
          $manager->flush();

        $this->addFlash(
            'success',
            ' Un utilisateur a été supprimé avec succes'
        );
          return $this->redirect('user.index');
    }

    #[Route('/user', name: 'user')]
    public function read(UserRepository $userRepository, PaginatorInterface $paginator, Request $request): Response
    {
        // $user = $userRepository->findAll();

        $users = $paginator->paginate(
            $userRepository->findAll(),
            $request->query->getInt('page', 1), 
            7 /*limit per page*/
        );

        return $this->render('user/index.html.twig', [
            'users' => $users
        ]);
    }
}
