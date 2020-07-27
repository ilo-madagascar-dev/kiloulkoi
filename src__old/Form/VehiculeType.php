<?php

namespace App\Form;

use App\Entity\Energie;
use App\Entity\User;
use App\Entity\Vehicule;
use App\Entity\Categories;
use Shapecode\Bundle\HiddenEntityTypeBundle\Form\Type\HiddenEntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;

class VehiculeType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('description')
            ->add('marque')
            ->add('prix')
            ->add('categorie', HiddenEntityType::class, [
                // looks for choices from this entity
                'class' => Categories::class,
            ])
            ->add('user', HiddenEntityType::class, [
                // looks for choices from this entity
                'class' => User::class,

            ])
            ->add('energie', EntityType::class, [
                // looks for choices from this entity
                'class' => Energie::class,

                'choice_label' => 'libelle',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Vehicule::class,
        ]);
    }
}
