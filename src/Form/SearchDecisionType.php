<?php

namespace App\Form;

use App\Entity\Decision;
use App\Entity\Department;
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
    public function __construct()
    {
    }
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('search', SearchType::class, [
                'attr' => [
                    'class' => 'form-control mb-4'
                ],
                'label' => 'Chercher une décision',
                'label_attr' => [
                    'class' => 'form-label mt-5'
                ],
            ])
            ->add('domaines', EntityType::class, [
                'attr' => [
                    'class' => 'form-check mt-3 mb-3 d-flex justify-content-around '
                ],
                'class'    => Department::class,
                'expanded' => true,
                'multiple' => true,
            ])
            ->add('Status', ChoiceType::class, [
                'attr' => [
                    'class' => 'form-check mt-3 '
                ],
                'choices' => [
                Decision::STATUS[0] => Decision::STATUS[0],
                Decision::STATUS[1] => Decision::STATUS[1],
                Decision::STATUS[2] => Decision::STATUS[2],
                Decision::STATUS[3] => Decision::STATUS[3],
                Decision::STATUS[4] => Decision::STATUS[4],
                Decision::STATUS[5] => Decision::STATUS[5],
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
