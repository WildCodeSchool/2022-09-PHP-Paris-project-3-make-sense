<?php

namespace App\Form;

use App\Entity\Opinion;
use Symfony\Component\Form\AbstractType;
use FOS\CKEditorBundle\Form\Type\CKEditorType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class OpinionType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('message', CKEditorType::class, [
                'attr' => [
                    'required' => true,
                    'class' => 'form-control',
                ],
                'label' => 'Les risques',
                'label_attr' => [
                    'class' => 'form-label'
                ]])
            ->add('submit', SubmitType::class, [
                    'attr' => [
                        'class' => 'btn btn-secondary'
                        ]    
                ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Opinion::class,
        ]);
    }
}
