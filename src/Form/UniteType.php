<?php

namespace App\Form;

use App\Entity\TypeUnite;
use App\Entity\Unite;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Formulaire qui permet à un client de modifier une de ses unités.
 *
 * Le client peut seulement changer le nom et le type de l'unité.
 * L'état et l'emplacement sont gérés par WorkTogether, ils ne sont donc
 * pas dans le formulaire.
 */

class UniteType extends AbstractType
{
    /**
     * Liste des champs du formulaire.
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            // Le nom donné par le client (facultatif)

            ->add('nomPersonnalise', null, [
                'label' => 'Nom de l\'unité',
                'required' => false,
            ])
            // Le type de l'unité : une liste déroulante remplie avec les
            // types enregistrés en base (Serveur web, Base de données...)
            ->add('typeUnite', EntityType::class, [
                'class' => TypeUnite::class,
                'choice_label' => 'libelle',// texte affiché dans la liste
                'label' => 'Type',
                'required' => false,
                'placeholder' => 'Aucun type',
            ]);
    }

    /**
     * Indique que ce formulaire remplit un objet Unite.
     */
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Unite::class,
        ]);
    }
}
