<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SearchType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class SearchAnnonceType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('mots', SearchType::class, [
                'label' => false,
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => 'Entrez un ou plusieurs mots-clées'
                ],
                'required' => false,
            ])

            ->add('price', SearchType::class,[
                'label' => false,
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => 'Prix'
                ],
                'required' => false
            ])

            ->add('category', ChoiceType::class, [
                'choices'  => [
                    'Voiture' => 'Voiture',
                    'Vétement' => 'Vêtement',
                    'Jeux Vidéo' => 'Jeux Vidéo',
                    'Carte' => 'Carte',
                    'Instrument' => 'Instrument',
                    'Animal' => 'Animal',
                ],
                'label' => false,
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => 'Catégorie'
                ],
                'required' => false
            ])

            ->add('location', ChoiceType::class, [
                'choices'  => [
                    'Allemagne' => 'Allemagne',
                    'Belgique' => 'Belgique',
                    'France' => 'France',
                    'Suisse' => 'Suisse',
                    'Italie' => 'Italie',
                    'Portugal' => 'Portugal',
                    'Espagne' => 'Espagne'
                ],
                'label' => false,
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => 'Localisation'
                ],
                'required' => false
            ])

            ->add('Rechercher', SubmitType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            // Configure your form options here
        ]);
    }
}
