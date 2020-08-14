<?php

namespace App\Form\Category;

use App\Entity\Annonces;
use App\Entity\AnnonceVehicule;
use App\Form\AnnoncesType;
use App\Entity\Propriete;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class VehiculeType extends AnnoncesType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $annonce = new Annonces();
        $classe  = 'Vehicule';
        $options['categorie_id'] = $annonce->getCategoryId($classe);
        parent::buildForm($builder, $options);

        $builder
            ->add('marque')
            ->add('modele')
            ->add('energie', EntityType::class, [
                'class' => Propriete::class,
                'query_builder' => function (EntityRepository $er) 
                {
                    return $er->createQueryBuilder('p')
                        ->where('p.libelle = :libelle')
                        ->setParameter('libelle', 'energie');
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
