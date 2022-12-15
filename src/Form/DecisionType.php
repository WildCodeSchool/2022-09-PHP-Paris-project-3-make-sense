<?php

namespace App\Form;

use App\Entity\Decision;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;

class DecisionType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title')
            ->add('description')
            ->add('impacts')
            ->add('benefits')
            ->add('risks')
            ->add('likeThreshold')
            // ->add('createdAt', DateType::class)
            // ->add('updatedAt', DateType::class)
            // ->add('startedAt', DateType::class)
            // ->add('endedAt', DateType::class)
            ->add('createdBy')
            // ->add('contents')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Decision::class,
        ]);
    }
}
