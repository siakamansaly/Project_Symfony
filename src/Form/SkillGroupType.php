<?php

namespace App\Form;

use App\Entity\SkillGroup;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class SkillGroupType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title')
            ->add('emoji')
            ->add('organization', ChoiceType::class, [
                'choices' => [
                    'HardSkills' => 'HardSkills',
                    'SoftSkills' => 'SoftSkills',
                    'OtherSkills' => 'OtherSkills',
                    'Environment' => 'Environment',
                ],
                'placeholder' => 'Choose an organization',
                'required' => true,
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => SkillGroup::class,
        ]);
    }
}
