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

class SearchDecisionsType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $choicesDepartment = [];
        $choicesStatus = [];
        foreach (Department::DEPARTMENTS as $deparments => $departmentValue) {
            $choicesDepartment[$departmentValue] = $deparments;
        }
        foreach (Decision::STATUSES as $status => $statusValue) {
            $choicesStatus[$statusValue] = $status;
        }
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
            ->add('domaines', ChoiceType::class, [
                'attr' => [
                    'class' => 'form-check mt-3 mb-3 d-flex justify-content-around '
                ],
                'choices' => [
                    $choicesDepartment,
                ],
                'expanded' => true,
                'multiple' => true,
            ])
            ->add('Status', ChoiceType::class, [
                'attr' => [
                    'class' => 'form-check mt-3 '
                ],
                'choices' => [
                    $choicesStatus
                ],
            ]);
    }
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
        ]);
    }
}
