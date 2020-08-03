<?php

namespace App\Form;

use App\Entity\Categories;
use App\Entity\EnfantMode;
use App\Entity\Pointure;
use App\Entity\Taille;
use App\Entity\User;
use Doctrine\ORM\EntityRepository;
use Shapecode\Bundle\HiddenEntityTypeBundle\Form\Type\HiddenEntityType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class EnfantModeType extends AbstractType
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
            ->add('pointure', EntityType::class, [
                'class' => Pointure::class,
                'query_builder' => function (EntityRepository $er) {
                    $classe = 'EnfantMode';
                    return $er->createQueryBuilder('p')
                        ->where('p.classe = :classe')
                        ->setParameter('classe', $classe);
                },
                'choice_label' => 'libelle'])
            ->add('taille', EntityType::class, [
                'class' => Taille::class,
                'query_builder' => function (EntityRepository $er) {
                    $classe = 'EnfantMode';
                    return $er->createQueryBuilder('t')
                        ->where('t.classe = :classe')
                        ->setParameter('classe', $classe);
                },
                'choice_label' => 'libelle'])
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
            'data_class' => EnfantMode::class,
        ]);
    }
}
