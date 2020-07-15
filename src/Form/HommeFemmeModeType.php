<?php

namespace App\Form;

use App\Entity\Categories;
use App\Entity\HommeFemmeMode;
use App\Entity\Pointure;
use App\Entity\Taille;
use App\Entity\User;
use Doctrine\ORM\EntityRepository;
use Shapecode\Bundle\HiddenEntityTypeBundle\Form\Type\HiddenEntityType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class HommeFemmeModeType extends AbstractType
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
                    $classe = 'HommeFemmeMode';
                    return $er->createQueryBuilder('p')
                        ->where('p.classe = :classe')
                        ->setParameter('classe', $classe);
                },
                'choice_label' => 'libelle'])
            ->add('taille', EntityType::class, [
                'class' => Taille::class,
                'query_builder' => function (EntityRepository $er) {
                    $classe = 'HommeFemmeMode';
                    return $er->createQueryBuilder('t')
                        ->where('t.classe = :classe')
                        ->setParameter('classe', $classe);
                },
                'choice_label' => 'libelle'])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => HommeFemmeMode::class,
        ]);
    }
}
