<?php

namespace App\Form;

use App\Entity\Annonces;
use App\Entity\Location;
use App\Entity\StatutLocation;
use App\Entity\User;
use Shapecode\Bundle\HiddenEntityTypeBundle\Form\Type\HiddenEntityType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class LocationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('dateDebut')
            ->add('dateFin')
            ->add('dateReservation')
            ->add('statutLocation', EntityType::class, [
                // looks for choices from this entity
                'class' => StatutLocation::class,

                // uses the User.username property as the visible option string
                'choice_label' => 'libelle',

                // used to render a select box, check boxes or radios
                // 'multiple' => true,
                // 'expanded' => true,
            ])
            ->add('annonces', HiddenEntityType::class, [
                // looks for choices from this entity
                'class' => Annonces::class,

            ])
            ->add('user', HiddenEntityType::class, [
                // looks for choices from this entity
                'class' => User::class,

            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Location::class,
        ]);
    }
}
