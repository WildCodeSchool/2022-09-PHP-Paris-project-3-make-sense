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
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\DateType;


class UserType extends AbstractType
{
    // private DepartmentRepository $departmentRepository;
    
    //  public function __construct(DepartmentRepository $departmentRepository) 
    //  {
    //      $this->departmentRepository = $departmentRepository;
    //  }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        
        // $experts = $this->departmentRepository->findAllExpertiseByDepartement($options['data']->getId());
      
        // dd($options);

        $builder
            ->add('lastname', TextType::class, [
                'attr' => [
                    'class' => 'form-control',
                ],
                'label' => 'noms',
                'label_attr' => [
                    'class' => 'form-label mt-4'
                ],
            ])
             ->add('firstname', TextType::class, [
                 'attr' => [
                     'class' => 'form-control',
                 ],
                 'label' => 'Prénoms',
                 'label_attr' => [
                     'class' => 'form-label mt-4'
                 ],
             ])
             ->add('password', TextType::class, [
                'attr' => [
                    'class' => 'form-control',
                ],
                'label' => 'Mot de passe',
                'label_attr' => [
                    'class' => 'form-label mt-4'
                ],
            ])
             ->add('email', TextType::class, [
                 'attr' => [
                     'class' => 'form-control',
                 ],
                 'label' => 'Email',
                 'label_attr' => [
                     'class' => 'form-label mt-4'
                 ],
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
             ->add('posterFile', VichFileType::class, [
                 'required' => false,
                 'allow_delete' => true,
                 'download_uri' => true,
                 'label' => 'Photo de profil',
                 'label_attr' => [
                     'class' => 'form-label mt-4'
                 ]
             ])
      
             ->add('phone', TextType::class, [
                 'attr' => [
                     'class' => 'form-control',
                 ],
                 'label' => 'Numéro de téléphone',
                 'label_attr' => [
                     'class' => 'form-label mt-4'
                 ],
         
             ]            
            );
             

            //    $departments = $this->departmentRepository->findAll();

        $builder->add('departments', ExpertisesType::class, [
            'mapped' => false,
            'user' => $options['data']
        ]);


            // $builder->add('experts', CollectionType::class, 
            //     [ 'entry_type' => DepartmentType::class,
            //       'allow_add' => true,
            //       'prototype' => true,
            //         'mapped' => false,
            //         'entry_options' => ['label' => false]
            //         ]
            //     );
            // $builder->get('experts')
            //     ->add('expert_0', ChoiceType::class, [
            //     'label' => 'label1',
            //     'required' => true,
            //     'mapped' => false,
            //     // 'allow_add' => true,
            //     'choices' => [ 'aucune' => 'aucune', 'interet' => 'interet', 'expert' => 'expert'],
            //     'data'=> 'tete'
            // ]);

            //  $i = 0;
            // foreach ($experts as $key => $value) {
            //     $choice_value = 'aucune';

            //     if (is_null($value['isExpert'])) 
            //     {
            //         $choice_value = 'aucune';          
            //     } else {
            //         if ($value['isExpert'] == false) {
            //              $choice_value = 'interet';
            //          } else {
            //             $choice_value = 'expert';
            //         }
            //     }
            //     // $builder->get('experts')
            //     //     ->add('expert_' . $i, ChoiceType::class, [
            //     //     'label' => $value['dep_name'],
            //     //     'required' => true,
            //     //     'mapped' => false,
            //     //     // 'allow_add' => true,
            //     //     'choices' => [ 'aucune' => 'aucune', 'interet' => 'interet', 'expert' => 'expert'],
            //     //     'data'=> $choice_value
            //     // ]);
            //      $i++;
            //    }

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
           
            $builder->add('submit', SubmitType::class, [
                     'attr' => [
                         'class' => 'btn btn-danger mt-4',
                     ],
                     'label' => 'Enregistrer'
                 ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}