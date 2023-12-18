<?php
// src/Form/SecteurType.php

namespace App\Form;

use App\Entity\Secteur;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SecteurType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nom', null, [
                'label' => 'Nom du secteur',
                'data' => $options['secteur_nom'], // Utilisez l'option pour passer la valeur depuis le contrôleur
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Secteur::class,
            'secteur_nom' => null, // Ajoutez une option personnalisée pour stocker le nom du secteur
        ]);
    }
}
