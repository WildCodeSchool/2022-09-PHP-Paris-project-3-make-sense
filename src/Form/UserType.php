<?php

namespace App\Form;

use App\Entity\User;
use App\Entity\Department;
use App\Repository\DepartmentRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\CallbackTransformer;
use Vich\UploaderBundle\Form\Type\VichFileType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;


class UserType extends AbstractType
{
    private DepartmentRepository $departmentRepository;
    
    public function __construct(DepartmentRepository $departmentRepository) 
    {
        $this->departmentRepository = $departmentRepository;
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('lastname', TextType::class, [
                'attr' => [
                    'class' => 'form-control',
                    'minlength' => '1',
                    'maxlength' => '80'
                ],
                'label' => 'noms',
                'label_attr' => [
                    'class' => 'form-label mt-4'
                ],
                'constraints' => [
                    new Assert\Length(['min' => 1, 'max' => 255]),
                    new Assert\NotBlank()
                ]
            ])
            ->add('firstname', TextType::class, [
                'attr' => [
                    'class' => 'form-control',
                    'minlength' => '1',
                    'maxlength' => '80'
                ],
                'label' => 'Prénoms',
                'label_attr' => [
                    'class' => 'form-label mt-4'
                ],
                'constraints' => [
                    new Assert\Length(['min' => 1, 'max' => 80]),
                    new Assert\NotBlank()
                ]
            ])
            ->add('email', TextType::class, [
                'attr' => [
                    'class' => 'form-control',
                    'minlength' => '1',
                    'maxlength' => '180'
                ],
                'label' => 'Email',
                'label_attr' => [
                    'class' => 'form-label mt-4'
                ],
                'constraints' => [
                    new Assert\Length(['min' => 1, 'max' => 180]),
                    new Assert\Email()
                ]
            ])
            ->add('roles', ChoiceType::class, [
                'attr' => [
                    'class' => 'btn btn-danger dropdown-toggle" type="button" data-bs-toggle="dropdown mt-4',
                ],
                'label' => 'roles',
                'label_attr' => [
                    'class' => 'dropdown-menu'
                ],
                'choices' => [
                    'Admin' => 'ROLE_ADMIN',
                    'User' => 'ROLE_USER',

                ], 
            ])
            ->add('imageFile', VichFileType::class, [
                'required'      => false,
                'allow_delete'  => true, 
                'download_uri' => true, 
                'label' => 'Photo de profil',
                'label_attr' => [
                    'class' => 'form-label mt-4'
                ]
              ])
            ->add('phone', TextType::class, [
                'attr' => [
                    'class' => 'form-control',
                    'minlength' => '1',
                    'maxlength' => '12'
                ],
                'label' => 'Numéro de téléphone',
                'label_attr' => [
                    'class' => 'form-label mt-4'
                ],
                'constraints' => [
                    new Assert\LessThan(12),
                ]
            ])
            ->add('submit', SubmitType::class, [
                'attr' => [
                    'class' => 'btn btn-danger mt-4',
                ],
                'label' => 'Enrégistrer'
            ]);

            $departments = $this->departmentRepository->findAll();
            
            foreach ($departments as $department) {
                $builder
                ->add('interestedBy' . $department->getId(), CheckboxType::class, [
                    'label' => 'Interessé par ?',
                    'required' => false,
                    'mapped' => false,
                ])
                ->add('expertIn'  . $department->getId(), CheckboxType::class, [
                    'label' => 'Expert ?',
                    'required' => false,
                    'mapped' => false,
                ]);
            }

            $builder->get('roles')
            ->addModelTransformer(
                new CallbackTransformer(
                    function ($rolesArray) {
                        return count($rolesArray) ? $rolesArray[0] : null;
                    },
                    function ($rolesString) {
                        return [$rolesString];
                    }
            ));
            
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
