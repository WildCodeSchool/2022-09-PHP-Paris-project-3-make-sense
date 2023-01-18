<?php

namespace App\Form;

use App\Entity\User;
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

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'user' => null
        ]);
    }
    
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $experts = $this->departmentRepository->findAllExpertiseByDepartement($options['data']->getId());
         dd($options);
   
        foreach ($experts as $key => $value) {
                 $choice_value = 'aucune';

                 if (is_null($value['isExpert'])) 
                 {
                     $choice_value = 'aucune';          
                 } else {
                     if ($value['isExpert'] == false) {
                          $choice_value = 'interet';
                      } else {
                         $choice_value = 'expert';
                     }
                 }

                  $builder->add($value['dep_name'])
                      ->add('expert_' . $i, ChoiceType::class, [
                      'label' => $value['dep_name'],
                      'required' => true,
                      'mapped' => false,
                       'allow_add' => true,
                      'choices' => [ 'aucune' => 'aucune', 'interet' => 'interet', 'expert' => 'expert'],
                      'data'=> $choice_value
                  ]);
                  $i++;
                }

            ->add(
                'rh', ChoiceType::class,
                [
                    'label' => 'Ressources humaines',
                    'required' => true,
                    'choices' => ['aucune' => 'aucune', 'interet' => 'interet', 'expert' => 'expert'],
                ]
            )
            );
    }
}