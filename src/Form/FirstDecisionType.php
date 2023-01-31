<?php

namespace App\Form;

use App\Entity\Decision;
use Symfony\Component\Form\AbstractType;
use FOS\CKEditorBundle\Form\Type\CKEditorType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class FirstDecisionType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        if ($options['data']->getStatus() == Decision::STATUS_CONFLICT) {
            $builder
                ->add('endAt', DateType::class, [
                    'label' => 'Veuillez indiquer la nouvelle deadline',
                    'input'  => 'datetime_immutable',
                ]);
        }

        $builder
            ->add('report', CKEditorType::class, [
                'label' => false,
                'empty_data' => ''
            ])
            ->add('submit', SubmitType::class, [
                'attr' => [
                    'class' => 'mb-2 rounded-5 col-2 card-bg-color text-white'
                ]
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Decision::class,
        ]);
    }
}
