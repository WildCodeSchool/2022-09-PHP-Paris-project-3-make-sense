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
                    'class' => 'form-label'
                ]
            ])
            ->add('description', CKEditorType::class, [
                'attr' => [
                    'required' => true,
                    'class' => 'form-control',
                ],
                'label' => 'Description',
                'label_attr' => [
                    'class' => 'form-label'
                ]
            ])
            ->add('impacts', CKEditorType::class, [
                'attr' => [
                    'required' => true,
                    'class' => 'form-control',
                ],
                'label' => 'Les impacts',
                'label_attr' => [
                    'class' => 'form-label'
                ]
            ])
            ->add('benefits', CKEditorType::class, [
                'attr' => [
                    'required' => true,
                    'class' => 'form-control',
                ],
                'label' => 'Les bénéfices',
                'label_attr' => [
                    'class' => 'form-label'
                ]
            ])
            ->add('risks', CKEditorType::class, [
                'attr' => [
                    'required' => true,
                    'class' =>'form-control',
                ],
                'label' => 'Les risques',
                'label_attr' => [
                    'class' => 'form-label'
                ]
            ])
            ->add('likeThreshold', IntegerType::class, [
                'label' => 'Avis négatifs générant'])
            ->add('department', EntityType::class, [
                'class' => Department::class,
                'choice_label' => 'name',
                'multiple' => true,
                'label' => 'Domaine'
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
}
