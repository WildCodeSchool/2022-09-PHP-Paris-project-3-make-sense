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
                    'class' => 'form-control mb-4',
                    'value' => $options['data']['title'],
                ],
                'label' => 'Chercher une décision',
                'label_attr' => [
                    'class' => 'form-label  text-white'
                ],
                'required' => false,
            ])
            ->add('departements', ChoiceType::class, [
                'attr' => [
                    'class' => 'form-check mt-3 mb-3 d-flex justify-content-around text-white'
                ],
                'choices' =>  $choicesDepartment,
                'expanded' => 'checked',
                'multiple' => 'checked',
                'data' => [true],
                'label' => 'departements',
                'label_attr' => ['switch_custom', 'class' => 'text-white'],
                ])
            ->add('status', ChoiceType::class, [
                'attr' => [
                    'class' => 'form-check mt-3'
                ],
                'required' => 'checked',
                'choices' => $choicesStatus,
                'label_attr' => ['class' => 'text-white'],
            ]);
    }
}
