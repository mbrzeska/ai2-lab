<?php

namespace App\Form;

use App\Entity\Location;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class LocationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $choices = [
            'France' => 'FR',
            'Italy' => 'IT',
            'Spain' => 'ES',
            'Portugal' => 'PT',
            'Poland' => 'PL',
            'United States' => 'US',
            'United Kingdom' => 'GB',
            'Australia' => 'AU',
            'Japan' => 'JP',
            'Czechia' => 'CZ',
        ];
        ksort($choices); //sortuje alfabetycznie kraje

        $builder
            ->add('city', null, [
                'attr' => [
                    'placeholder' => 'City name',
                ]
            ])
            ->add('country',ChoiceType::class, [
                'choices' => $choices,
            ])
            ->add('latitude', null, [
                'attr' => [
                    'placeholder' => 'e.g. 53.4469',
                ]
            ])
            ->add('longitude', null, [
                'attr' => [
                    'placeholder' => 'e.g. 14.4924',
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Location::class,
        ]);
    }
}
