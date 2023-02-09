<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\SearchType;

class SearchTitleType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('search_title', SearchType::class, [
                'attr' => [
                    'class' => 'form-control mt-2 col-3'
                ],
                'label' => false,
                'required' => false
            ]);
    }
}
