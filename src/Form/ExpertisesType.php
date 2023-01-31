<?php

namespace App\Form;

use App\Entity\Department;
use App\Repository\DepartmentRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class ExpertisesType extends AbstractType
{
    private DepartmentRepository $departmentRepository;

    public function __construct(DepartmentRepository $departmentRepository)
    {
        $this->departmentRepository = $departmentRepository;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'user' => null
        ]);
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $user = $options['user'];

        $experts = $this->departmentRepository->findAllExpertiseByDepartement($user->getId());

        foreach ($experts as $key => $expert) {
            $key = "";
            $choiceValue = 'aucune';
            if (is_null($expert['isExpert'])) {
                $choiceValue = 'aucune';
            } else {
                if ($expert['isExpert'] == false) {
                    $choiceValue = 'interet';
                } else {
                    $choiceValue = 'expert';
                }
            }
            $builder->add($expert['dep_name'])
                ->add($expert['dep_name'], ChoiceType::class, [
                    'label' => Department::DEPARTMENTS[$expert['dep_name']],
                    'required' => true,
                    'mapped' => false,
                    //    'allow_add' => true,
                    'choices' => ['aucune' => 'aucune', 'interet' => 'interet', 'expert' => 'expert'],
                    'data' => $choiceValue
                ]);
        }
    }
}
