<?php

namespace App\Form;

use App\Entity\Decision;
use App\Entity\Department;
use Symfony\Component\Form\AbstractType;
use FOS\CKEditorBundle\Form\Type\CKEditorType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\RangeType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Validator\Constraints\GreaterThanOrEqual;

class DecisionType extends AbstractType
{
    public function buildForm(
        FormBuilderInterface $builder,
        array $options
    ): void {
        $choiceDepartments = [];

        foreach (Department::DEPARTMENTS as $departmentValue) {
            $choiceDepartments[$departmentValue] = $departmentValue;
        }

        $builder
            ->add('title', TextType::class, [
                'required' => false,
                'attr' => [
                    'class' => 'form-control',
                    'length' => '80',],
                'label' => 'Titre',
                'label_attr' => [
                    'class' => 'form-label h4 d-flex justify-content-start mb-3 mt-3']])
            // ->add('departments', ChoiceType::class, [
            //     'required' => true,
            //     'attr' => [
            //         'class' => 'form-check'],
            //     'choices' => $choiceDepartments,
            //     'mapped' => false,
            //     'expanded' => true,
            //     'multiple' => true,])
            ->add('departments', EntityType::class, [
                 'class' => Department::class,
                 'required' => true,
                 'attr' => [
                     'class' => 'form-check'],
                 'expanded' => true,
                 'multiple' => true,])
            ->add('like_threshold', RangeType::class, [
                'required' => true,
                'attr' => [
                    'min' => '1',
                    'max'  => '100',
                    'value' => '50',
                    'class' => 'col-2 form-range slider',
                    'id' => "myRange",],
                'constraints' => [
                    new Assert\PositiveOrZero(),],
                'label' => 'Avis négatifs générant un conflit (%)',
                'label_attr' => [
                    'class' => 'form-label h4 d-flex justify-content-start mb-3 mt-3'],])
            ->add('end_at', DateType::class, [
                'required' => true,
                'constraints' => [
                    new NotBlank(),
                    new GreaterThanOrEqual('today', message:'Cette valeur ne peut être inférieur à la date du jour'),
                ],
                'widget' => 'single_text',
                'attr' => ['class' => 'js-datepicker form-control'],
                'label' => 'Date de fin',
                'label_attr' => [
                    'class' => 'form-label h4 d-flex justify-content-start mb-3 mt-3']])
            ->add('description', CKEditorType::class, [
                'required' => false,
                'attr' => [
                    'class' => 'form-control',],
                'label' => 'Description',
                'label_attr' => [
                    'class' => 'form-label h4 d-flex justify-content-start mb-3 mt-3']])
            ->add('impacts', CKEditorType::class, [
                'required' => true,
                'attr' => [
                    'class' => 'form-control',],
                'label' => 'Les impacts',
                'label_attr' => [
                    'class' => 'form-label h4 d-flex justify-content-start mb-3 mt-3']])
            ->add('benefits', CKEditorType::class, [
                'required' => true,
                'attr' => [
                    'class' => 'form-control',],
                'label' => 'Les bénéfices',
                'label_attr' => [
                    'class' => 'form-label h4 d-flex justify-content-start mb-3 mt-3']])
            ->add('risks', CKEditorType::class, [
                'required' => true,
                'attr' => [
                    'class' => 'form-control',],
                'label' => 'Les risques',
                'label_attr' => [
                    'class' => 'form-label h4 d-flex justify-content-start mb-3 mt-3']])
            ->add('saveAsDraft', SubmitType::class, [
                'attr' => [
                    'class' => 'btn btn-secondary card-bg-color'],
                'label' => 'Enregistrer en tant que brouillon',])
            ->add('save', SubmitType::class, [
                'attr' => [
                    'class' => 'btn btn-secondary card-bg-color',],
                'label' => 'Soumettre',]);
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Decision::class,
        ]);
    }
}
