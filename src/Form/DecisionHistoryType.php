<?php

namespace App\Form;

use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use App\Entity\DecisionHistory;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class DecisionHistoryType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('startedAt')
            ->add('endedAt')
            ->add('status', ChoiceType::class, [
                'choices' => [
                    'Brouillon' => 'Brouillon',
                    'En cours' => 'En cours',
                    '1ere décision prise' => '1ere décision prise',
                    'Conflit' => 'Conflit',
                    'Aboutie' => 'Aboutie',
                    'Non aboutie' => 'Non aboutie'
                ]
            ])
            ->add('decision');
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => DecisionHistory::class,
        ]);
    }
}
