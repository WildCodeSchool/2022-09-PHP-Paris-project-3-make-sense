<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\CallbackTransformer;
use Vich\UploaderBundle\Form\Type\VichFileType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;

/** @SuppressWarnings(PHPMD.TooManyPublicMethods)
 *   @SuppressWarnings(PHPMD.ExcessivePublicCount)
 */
class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('lastname', TextType::class, [
                'attr' => [
                    'class' => 'form-control',
                ],
                'label' => 'Nom',
                'label_attr' => [
                    'class' => 'form-label mt-4'
                ],
            ])
            ->add('firstname', TextType::class, [
                'attr' => [
                    'class' => 'form-control',
                ],
                'label' => 'Prénom',
                'label_attr' => [
                    'class' => 'form-label mt-4'
                ],
            ])
            ->add('password', PasswordType::class, [
                'required' => false,
                // 'mapped' => false,
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
                'allow_delete'  => false, // not mandatory, default is true
                'download_uri' => false, // not mandatory, default is true
                'label' => 'Photo de profil',
                'label_attr' => [
                    'class' => 'form-label mt-4'
                ]
            ])
            ->add(
                'phone',
                TextType::class,
                [
                    'attr' => [
                        'class' => 'form-control',
                    ],
                    'label' => 'Numéro de téléphone',
                    'label_attr' => [
                        'class' => 'form-label mt-4'
                    ],
                ]
            );
        $builder->add('departments', ExpertisesType::class, [
            'mapped' => false,
            'user' => $options['data']
        ]);
        $builder->get('roles')
            ->addModelTransformer(
                new CallbackTransformer(
                    function ($rolesArray) {
                        return count($rolesArray) ? $rolesArray[0] : null;
                    },
                    function ($rolesString) {
                        return [$rolesString];
                    }
                )
            );
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
