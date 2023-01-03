<?php

namespace App\Form;

use App\Entity\Department;
use App\Entity\Decision;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use FOS\CKEditorBundle\Form\Type\CKEditorType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;

class DecisionType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title', TextType::class, [
                'attr' => [
                    'required' => true,
                    'class' => 'form-control',
                    'length' => '255',
                ],
                'label' => 'Titre',
                'label_attr' => [
                    'class' => 'form-label h4 d-flex justify-content-center mb-3 mt-3'
                ]
            ])
            ->add('description', CKEditorType::class, [
                'attr' => [
                    'required' => true,
                    'class' => 'form-control',
                ],
                'label' => 'Description',
                'label_attr' => [
                    'class' => 'form-label h4 d-flex justify-content-center mb-3 mt-3'
                ]
            ])
            ->add('impacts', CKEditorType::class, [
                'attr' => [
                    'required' => true,
                    'class' => 'form-control',
                ],
                'label' => 'Les impacts',
                'label_attr' => [
                    'class' => 'form-label h4 d-flex justify-content-center mb-3 mt-3'
                ]
            ])
            ->add('benefits', CKEditorType::class, [
                'attr' => [
                    'required' => true,
                    'class' => 'form-control',
                ],
                'label' => 'Les bénéfices',
                'label_attr' => [
                    'class' => 'form-label h4 d-flex justify-content-center mb-3 mt-3'
                ]
            ])
            ->add('risks', CKEditorType::class, [
                'attr' => [
                    'required' => true,
                    'class' =>'form-control',
                ],
                'label' => 'Les risques',
                'label_attr' => [
                    'class' => 'form-label h4 d-flex justify-content-center mb-3 mt-3'
                ]
            ])
            ->add('likeThreshold', IntegerType::class,[
                'attr' => [
                    'required' => true,
                    'class' => 'form-control',
                ],
                'label' => 'Avis négatifs générant un conflit (%)',
                'label_attr' => [
                    'class' => 'form-label h4 d-flex justify-content-center mb-3 mt-3'
                ]
                    ])

            ->add('department', EntityType::class, [
                'attr' => [
                    'required' => true,
                    'class' => 'form-control h-50',
                    
                ],
                'class' => Department::class,
                'choice_label' => 'name',
                'multiple' => true,
                'label' => 'Domaine',
                'label_attr' => [
                    'class' => 'form-label h4 d-flex justify-content-center mb-3 mt-3'
                ]
            ])
            ->add('submit', SubmitType::class, [
                'attr' => [
                    'class' => 'btn btn-secondary'
                ]
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
