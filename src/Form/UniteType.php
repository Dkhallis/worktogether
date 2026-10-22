<?php

namespace App\Form;

use App\Entity\TypeUnite;
use App\Entity\Unite;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UniteType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nomPersonnalise', null, [
                'label' => 'Nom de l\'unité',
                'required' => false,
            ])
            ->add('typeUnite', EntityType::class, [
                'class' => TypeUnite::class,
                'choice_label' => 'libelle',
                'label' => 'Type',
                'required' => false,
                'placeholder' => 'Aucun type',
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Unite::class,
        ]);
    }
}
