<?php

namespace App\Form;

use App\Entity\Department;
use App\Entity\History;
use App\Repository\DepartmentRepository;
use App\Repository\HistoryRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\SearchType;

class SearchDecisionType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('search', SearchType::class, [
                'attr' => [
                    'class' => 'form-control '
                ],
                'label' => 'Chercher une décision',
                'label_attr' => [
                    'class' => 'form-label mt-5'
                ],
            ])
            ->add('domaines', EntityType::class, [
                'class'    => Department::class,
                'expanded' => true,
                'multiple' => true,
            ])
            ->add('Status', ChoiceType::class, [
                'choices' => [
                   
                 History::STATUS[0] => History::STATUS[0],
                 History::STATUS[1] => History::STATUS[1],
                 History::STATUS[2] => History::STATUS[2],
                 History::STATUS[3] => History::STATUS[3],
                 History::STATUS[4] => History::STATUS[4],
                 History::STATUS[5] => History::STATUS[5],
                ],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            // Configure your form options here
        ]);
    }
}
