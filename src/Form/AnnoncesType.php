<?php

namespace App\Form;

use App\Entity\Annonces;
use App\Entity\Vehicule;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use App\Entity\Categories;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use App\Form\VehiculeType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;

class AnnoncesType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
                ->add('categorie', EntityType::class, [
                        // looks for choices from this entity
                        'class' => Categories::class,

                        // uses the User.username property as the visible option string
                        'choice_label' => 'libelle',

                        // used to render a select box, check boxes or radios
                        // 'multiple' => true,
                        // 'expanded' => true,
                        ])
                ->add('vehicule', VehiculeType::class, array('required' => false))
            ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Annonces::class,
        ]);
    }
}
