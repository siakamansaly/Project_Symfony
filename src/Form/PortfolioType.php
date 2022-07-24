<?php

namespace App\Form;

use App\Entity\Portfolio;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\FileType;

class PortfolioType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title')
            ->add('createdAt', DateType::class, [
                'widget' => 'single_text',
                'required' => false,
            ])
            ->add('cover', FileType::class, [
                'label' => 'Cover',
                'required' => false,
                'data_class' => null,
            ])
            ->add('content')
            ->add('link', UrlType::class, [
                'label' => 'Link',
                'required' => false,
                'attr' => [
                    'placeholder' => 'https://example.com',
                ],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Portfolio::class,
        ]);
    }
}
