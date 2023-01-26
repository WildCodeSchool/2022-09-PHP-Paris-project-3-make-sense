<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use DateTimeImmutable;
use App\Entity\Expertise;
use App\Entity\Department;
use App\Repository\UserRepository;
use App\Repository\ExpertiseRepository;
use App\Repository\DepartmentRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserController extends AbstractController
{
    private ExpertiseRepository $expertiseRepository;
    private DepartmentRepository $departmentRepository;
    private UserPasswordHasherInterface $passwordHasher;

    public function __construct(
        ExpertiseRepository $expertiseRepository,
        DepartmentRepository $departmentRepository,
        UserPasswordHasherInterface $passwordHasher
    ) {
        $this->expertiseRepository = $expertiseRepository;
        $this->departmentRepository = $departmentRepository;
        $this->passwordHasher = $passwordHasher;
    }

    public function updateExpertise(
        array $formDepartments,
        USER $user,
    ): void {

        foreach ($formDepartments as $key => $department) {
            $dept = $this->departmentRepository->findOneBy(['name' => $key]);
            $expertise = $this->expertiseRepository->findOneBy([
                'user' => $user,
                'department' => $dept
            ]);

            if (empty($expertise)) {
                $expertise = new Expertise();
                $expertise->setUser($user);
                $expertise->setDepartment($dept);
            }

            switch ($department) {
                case 'interet':
                    $expertise->setIsExpert(false);
                    $this->expertiseRepository->save($expertise, true);
                    break;
                case 'expert':
                    $expertise->setIsExpert(true);
                    $this->expertiseRepository->save($expertise, true);
                    break;
                case 'aucune':
                    if ($expertise->getId() != null) {
                        $this->expertiseRepository->remove($expertise, true);
                    }
                    break;
                default:
                    break;
            }
        }
    }


    #[Route('/user/edit/{id}', name: 'user_edit', methods: ['POST', 'GET'])]
    public function edit(UserRepository $userRepository, User $user, Request $request): Response
    {

        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $this->updateExpertise($request->request->all()['user']['departments'], $user);
            $userRepository->save($user, true);
            return $this->redirectToRoute('user');
        }

        return $this->render('User/edit.html.twig', [
            'form' => $form->createView()
        ]);
    }

    #[Route('/user/new', name: 'user_new', methods: ['POST', 'GET'])]
    public function new(
        Request $request,
        EntityManagerInterface $manager,
    ): Response {
        $user = new User();
        $form = $this->createForm(UserType::class, $user);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $user = $form->getData();
            $user->setUpdatedAt(new DateTimeImmutable('now'));
            $user->setRoles($user->getRoles());
            $user->setPassword(
                $this->passwordHasher->hashPassword(
                    $user,
                    $form->get('password')->getData()
                )
            );

            $manager->persist($user);
            $manager->flush();

            $this->updateExpertise(
                $request->request->all()['user']['departments'],
                $user
            );

            return $this->redirectToRoute('user');
        }

        return $this->render('User/new.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/user/delete/{id}', name: 'user_delete', methods: ['GET'])]
    public function delete(EntityManagerInterface $manager, ?User $user): Response
    {
        if (!$user) {
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
        $users = $paginator->paginate(
            $userRepository->findAll(),
            $request->query->getInt('page', 1),
            6 /*limit per page*/
        );


        return $this->render('user/index.html.twig', [
            'users' => $users,
        ]);
    }
}
