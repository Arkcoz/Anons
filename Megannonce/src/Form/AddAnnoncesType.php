<?php

namespace App\Form;

use App\Entity\Annonce;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class AddAnnoncesType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title')
            ->add('price')
            ->add('location', ChoiceType::class, [
                'choices'  => [
                    'Allemagne' => 'Allemagne',
                    'Belgique' => 'Belgique',
                    'France' => 'France',
                    'Suisse' => 'Suisse',
                    'Italie' => 'Italie',
                    'Portugal' => 'Portugal',
                    'Espagne' => 'Espagne'
                ]
            ])
            ->add('category',ChoiceType::class, [
                'choices'  => [
                    'Voiture' => 'Voiture',
                    'Vétement' => 'Vêtement',
                    'Jeux Vidéo' => 'Jeux Vidéo',
                    'Carte' => 'Carte',
                    'Instrument' => 'Instrument',
                    'Animal' => 'Animal',
                ]
            ])
            ->add('description')
            ->add('etat', ChoiceType::class, [
                'choices'  => [
                    'Neuf' => 'neuf',
                    'Très bon' => 'très bon',
                    'Bon' => 'bon',
                    'Satisfaisant' => 'satisfaisant',
                    'Usé' => 'usé'
                ],
            ])

            ->add('images', FileType::class, [
                'label' => false,
                'multiple' => true,
                'mapped' => false,
                'required' => false
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Annonce::class,
        ]);
    }
}
