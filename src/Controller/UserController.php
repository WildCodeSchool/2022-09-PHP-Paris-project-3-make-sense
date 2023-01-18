<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\Expertise;
use App\Form\UserType;
use DateTimeImmutable;
use App\Repository\UserRepository;
use App\Repository\ExpertiseRepository;
use App\Repository\DepartmentRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\BrowserKit\Response as BrowserKitResponse;

class UserController extends AbstractController
{
    public const USERID = 2;

    public function updateExpertise($form, $experts, ExpertiseRepository $expertiseRepository, USER $user, $departmentRepository): void
    {

        // dd($experts);
        // dd($form->getData());

        // dd( $expertiseRepository->findOneById(110));

    foreach ($experts as $key => $value) {
            if ($value['exp_id'] == null) {
                // dd($form['expert' . $key]->getData());
                if ($form['expert' . $key]->getData()) {
                    $expertise = new Expertise();
                    $expertise->SetUser($user);
                    // if ($expert['isExpert'] == null)
                    // dd('1')  ;
                    $expertise->setisExpert($form['expert' . $key]->getData()  == 'expert' ? true : false);
                    $expertise->setDepartment($departmentRepository->findOneById($value['dep_id']));
                    // dd($expertise);
                    $expertiseRepository->save($expertise, true);
                }
                else {
                    // a faire
                }
            }
            else {
                    // dd($value['exp_id']);
                    // dd($expertiseRepository->findOneById((int)($value['exp_id'])));

                    // $expertise = $expertiseRepository->findOneById((int)$value['dep_id']);
                    $expertise = $expertiseRepository->findOneById((int)($value['exp_id']));
                    // dd($expertise);
                    // if ($expert['isExpert'] == null)
                    // dd($form['expert' . $key]->getData());
                    if ($form['expert' . $key]->getData() == 'aucune') {
                        // dd('remove ' .  $form['expert' . $key]->getData());
                        // dd($expertise);
                        $expertiseRepository->remove($expertise, true);
                    
                    }
                    else {
                        $expertise->setisExpert($form['expert' . $key]->getData() == 'expert' ? true : false );
                        // dd($expertise);
                          // dd($form['expert0']->getData()
                        $expertiseRepository->save($expertise, true);
                    }
                }
        }
    }

    #[Route('/user/edit/{id}', name: 'user_edit', methods: ['POST' , 'GET'])]
    public function edit( UserRepository $userRepository, User $user, EntityManagerInterface $manager, Request $request, DepartmentRepository $departmentRepository, ExpertiseRepository $expertiseRepository): Response
    {
        // $userId = 2;

        // $experts = $departmentRepository->findAllExpertiseByDepartement($userId);

        // $user = new User();

        // dd($experts);
      $form = $this->createForm(UserType::class, $user);
        
        $form->handleRequest($request);
        // $user->setUpdatedAt(new DateTimeImmutable('now'));

    if ($form->isSubmitted()){
            // dd($form['expert0']->getData());
            // dd($form->getErrors());
            // dd($form->isValid());
            // dd($user->getExpertise()[0]);
            // dd( $expertiseRepository->findOneById(110));
            $experts = $departmentRepository->findAllExpertiseByDepartement(self::USERID);
            $this->updateExpertise($form, $experts, $expertiseRepository, $user, $departmentRepository);
            // dd($form['expert0']->getData());
            $userRepository->save($user, true);   
            //  $user = $form->getData();

            //  $manager->persist($user);
            //  $manager->flush();
            dd('save done');
            $this->redirectToRoute('security.login');
        }

        return $this->render('user/edit.html.twig', [
            'form' => $form->createView(),
            //  'expertises' => $expertises,
        ]);
    }

    #[Route('/user/new', name: 'user_new', methods: ['POST' , 'GET'])]
    public function new(Request $request, EntityManagerInterface $manager): Response
    {
        $user = new User();
        $form = $this->createForm(UserType::class, $user);

        $form->handleRequest($request);
        if($form->isValid() && $form->isSubmitted())
        {
            $user = $form->getData();
            
            $manager->persist($user);
            $manager->flush();

           return $this->redirectToRoute('user.index');
        }

        return $this->render('user/new.html.twig', [
            'form' => $form->createView()
        ]);
    }

    #[Route('/user/delete/{id}', name: 'user_delete', methods: ['POST'])]
    public function delete(EntityManagerInterface $manager, User $user): Response
    {     
        if (!$user){
            return $this->redirect('user.index');
        }
          $manager->remove($user);
          $manager->flush();

        $this->addFlash(
            'success',
            'l'
        );
          return $this->redirect('user.index');
    }
}
