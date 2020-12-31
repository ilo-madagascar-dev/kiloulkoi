<?php

namespace App\Form\Category;

use App\Entity\Annonces;
use App\Entity\AnnonceVehicule;
use App\Form\AnnoncesType;
use App\Entity\Energie;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class VehiculeType extends AnnoncesType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $options['classe'] = 'Vehicule';
        parent::buildForm($builder, $options);

        $builder
            ->add('marque')
            ->add('modele', TextType::class, ['label' => 'Modèle'])
            ->add('energie', EntityType::class, [
                'class' => Energie::class,
                'query_builder' => function (EntityRepository $er)
                {
                    return $er->createQueryBuilder('p')
                            ->orderBy('p.id');
                },
                'choice_label' => 'valeur'
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => AnnonceVehicule::class,
        ]);
    }
}
