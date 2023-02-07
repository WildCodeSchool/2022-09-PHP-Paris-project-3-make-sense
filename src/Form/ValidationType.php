<?php

namespace App\Form;

use App\Entity\Validation;
use Symfony\Component\Form\AbstractType;
use FOS\CKEditorBundle\Form\Type\CKEditorType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class ValidationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder->add('comment', CKEditorType::class, [
            'label' => false,
            'empty_data' => ''
        ]);

        if ($options['state'] == 'like') {
            $builder->add('avispositif', SubmitType::class, [
                'attr' => [
                    'class' => 'mt-2 mb-2 rounded-5 col-4 card-bg-color text-white'
                ],
            ]);
        }

        if ($options['state'] == 'dislike') {
            $builder->add('avisnegatif', SubmitType::class, [
                'attr' => [
                    'class' => 'mt-2 mb-2 rounded-5 col-4 card-bg-color text-white'
                ]
            ]);
        }
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Validation::class,
            'state' => ''
        ]);
    }
}
