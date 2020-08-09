<?php

namespace App\Form\Category;

use App\Entity\Pointure;
use App\Entity\Taille;
use App\Entity\VetementMaternite;
use App\Form\AnnoncesType;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class VetementMaterniteType extends AnnoncesType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        parent::buildForm($builder, $options);
        $builder
            ->add('taille', EntityType::class, [
                'class' => Taille::class,
                'query_builder' => function (EntityRepository $er) {
                    $classe = 'VetementMaternite';
                    return $er->createQueryBuilder('t')
                        ->where('t.classe = :classe')
                        ->setParameter('classe', $classe);
                },
                'choice_label' => 'libelle'])
            ->add('pointure', EntityType::class, [
                'class' => Pointure::class,
                'query_builder' => function (EntityRepository $er) {
                    $classe = 'VetementMaternite';
                    return $er->createQueryBuilder('p')
                        ->where('p.classe = :classe')
                        ->setParameter('classe', $classe);
                },
                'choice_label' => 'libelle'])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => VetementMaternite::class,
        ]);
    }
}
