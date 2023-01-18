<?php

namespace App\Form;

use App\Entity\Department;
use App\Entity\Decision;
use DateTimeImmutable;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use FOS\CKEditorBundle\Form\Type\CKEditorType;
use Symfony\Component\Form\Extension\Core\Type\PercentType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Form\Extension\Core\Type\ButtonType;

class DecisionType extends AbstractType
{
    public function buildForm(
        FormBuilderInterface $builder,
        array $options
        ): void {
        $builder
            ->add('title', TextType::class, [
                'constraints' => new NotBlank(),
                'attr' => [
                    'required' => true,
                    'class' => 'form-control',
                    'length' => '255',
                ],
                'label' => 'Titre',
                'label_attr' => [
                    'class' => 'form-label h4 d-flex justify-content-start mb-3 mt-3'
                ]
            ])
            ->add('description', CKEditorType::class, [
                'constraints' => new NotBlank(),
                'attr' => [
                    'required' => true,
                    'class' => 'form-control',
                ],
                'label' => 'Description',
                'label_attr' => [
                    'class' => 'form-label h4 d-flex justify-content-start mb-3 mt-3'
                ]
            ])
            ->add('end_at', DateTimeType::class, [
                'constraints' => new NotBlank(),
                'required' => false,
                'widget' => 'single_text',
                'attr' => ['class' => 'js-datepicker'],
                'label' => 'Date de fin',
                'label_attr' => [
                    'class' => 'form-label h4 d-flex justify-content-start mb-3 mt-3']
            ])
            ->add('impacts', CKEditorType::class, [
                'constraints' => new NotBlank(),
                'attr' => [
                    'required' => true,
                    'class' => 'form-control',
                ],
                'label' => 'Les impacts',
                'label_attr' => [
                    'class' => 'form-label h4 d-flex justify-content-start mb-3 mt-3'
                ]
            ])
            ->add('benefits', CKEditorType::class, [
                'constraints' => new NotBlank(),
                'attr' => [
                    'required' => true,
                    'class' => 'form-control',
                ],
                'label' => 'Les bénéfices',
                'label_attr' => [
                    'class' => 'form-label h4 d-flex justify-content-start mb-3 mt-3'
                ]
            ])
            ->add('risks', CKEditorType::class, [
                'constraints' => new NotBlank(),
                'attr' => [
                    'required' => true,
                    'class' => 'form-control',
                ],
                'label' => 'Les risques',
                'label_attr' => [
                    'class' => 'form-label h4 d-flex justify-content-start mb-3 mt-3'
                ]
            ])
            ->add('like_threshold', PercentType::class, [
                'type' => 'integer',
                'attr' => [
                    'required' => true,
                    'class' => 'col-sm-4',
                ],
                'constraints' => [
                    new NotBlank(),
                ],
                'label' => 'Avis négatifs générant un conflit (%)',
                'label_attr' => [
                    'class' => 'form-label h4 d-flex justify-content-start mb-3 mt-3'
                ],
            ])

            ->add('departments', EntityType::class, [
                'constraints' => new NotBlank(),
                'attr' => [
                    'required' => true,
                    'class' => 'form-check',
                ],
                'class' => Department::class,
                'choice_label' => 'name',
                'expanded' => true,
                'multiple' => true,
                'label_attr' => [
                    'class' => 'form-label mt-4 h4'
                ],

            ])
            ->add('status', SubmitType::class, [
                'attr' => [
                    'class' => 'btn btn-secondary card-bg-color'
                ],
                'label' => 'Enregistrer en tant que brouillon',
            ])

            ->add('submit', SubmitType::class, [
                'attr' => [
                    'class' => 'btn btn-secondary card-bg-color',
                ],
                'label' => 'Soumettre',
            ]);
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Decision::class,
        ]);
    }
    /**
     */
}
