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
        $builder
            ->add('isLike', HiddenType::class, [
                'attr' => [
                    'class' => 'hiddenlikecheck',
                    'value' => $options['data']->isIsLike() == "true" ? "1" : "0"
                ]
            ]);

        $builder->add('message', CKEditorType::class, [
            'label' => false,
            'empty_data' => ''
        ])
            ->add('submit', SubmitType::class, [
                'attr' => [
                    'class' => 'mt-2 mb-2 button-make-sense'
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
