<?php

namespace App\Form;

use App\Entity\Opinion;
use Symfony\Component\Form\AbstractType;
use FOS\CKEditorBundle\Form\Type\CKEditorType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class OpinionType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        if ($options['data']->isIsLike() == null) {
            $builder
                ->add('isLike', HiddenType::class, [
                    'attr' => ['class' => 'likeCheck', 'value' => "0"]
                ]);
        } else {
            $builder
                ->add('isLike', HiddenType::class, [
                    'attr' => ['class' => 'likeCheck']
                ]);
        }

        $builder->add('message', CKEditorType::class, [
            'label' => false,
            'empty_data' => ''
        ])
            ->add('submit', SubmitType::class, [
                'attr' => [
                    'class' => 'mt-2 mb-2 rounded-5 col-2 card-bg-color text-white'
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
