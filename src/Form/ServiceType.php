<?php

namespace App\Form;

use App\Entity\Categories;
use App\Entity\Service;
use App\Entity\User;
use Shapecode\Bundle\HiddenEntityTypeBundle\Form\Type\HiddenEntityType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ServiceType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('titre')
            ->add('description')
            ->add('prix')
            ->add('categorie', HiddenEntityType::class, [
                // looks for choices from this entity
                'class' => Categories::class,
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
            'data_class' => Service::class,
        ]);
    }
}
