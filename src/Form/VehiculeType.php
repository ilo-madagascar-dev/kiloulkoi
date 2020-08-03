<?php

namespace App\Form;

use App\Entity\Energie;
use App\Entity\Photo;
use App\Entity\User;
use App\Entity\Vehicule;
use App\Entity\Categories;
use Shapecode\Bundle\HiddenEntityTypeBundle\Form\Type\HiddenEntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Validator\Constraints\File;

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
            ->add('photo', CollectionType::class ,[
                'entry_type' => PhotoType::class,
                'entry_options' => ['label' => false],
                'allow_add' => true,
                'prototype' => true,
                'allow_delete' => true,
                'by_reference' => false,
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
