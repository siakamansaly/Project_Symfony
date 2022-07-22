<?php

namespace App\Form;

use App\Entity\Experience;
use App\Form\ExperienceDetailsType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;

class ExperienceType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('dateStart', DateType::class, [
                'widget' => 'single_text',
                'required' => false,
            ])
            ->add('dateEnd', DateType::class, [
                'widget' => 'single_text',
                'required' => false,
            ])
            ->add('city')
            ->add('formation', ChoiceType::class, [
                'choices' => [
                    'Yes' => true,
                ],
                'expanded' => true,
                'multiple' => false,
                'empty_data' => null,
                'label' => 'Is this a formation ?',
                'required' => false,
            ])
            ->add('title')
            ->add('society')
            ->add('context')
            ->add('experienceDetails', CollectionType::class, [
                'entry_type' => ExperienceDetailsType::class,
                'label_attr' => ['class' => 'd-none'],
                'by_reference' => false,
                'allow_add' => true,
                'allow_delete' => true,
                'prototype' => true,

            ])
        ;
        
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Experience::class,
        ]);
    }
}
