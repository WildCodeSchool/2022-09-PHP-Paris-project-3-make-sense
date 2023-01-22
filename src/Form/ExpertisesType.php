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
        $user = $options['user'];

        $experts = $this->departmentRepository->findAllExpertiseByDepartement($user->getId());
        //  dd($options);
   
        foreach ($experts as $key => $expert) {
                 $choice_value = 'aucune';

                 if (is_null($expert['isExpert'])) 
                 {
                     $choice_value = 'aucune';          
                 } else {
                     if ($expert['isExpert'] == false) {
                          $choice_value = 'interet';
                      } else {
                         $choice_value = 'expert';
                     }
                 }
            // dd($expert['dep_name']);
                  $builder->add($expert['dep_name'])
                      ->add($expert['dep_name'], ChoiceType::class, [
                      'label' => Department::DEPARTMENTS[$expert['dep_name']],
                      'required' => true,
                      'mapped' => false,
                    //    'allow_add' => true,
                      'choices' => [ 'aucune' => 'aucune', 'interet' => 'interet', 'expert' => 'expert'],
                      'data'=> $choice_value
                  ]);
          
            }
    }
}