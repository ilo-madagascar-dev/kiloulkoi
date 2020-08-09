<?php

namespace App\Form\Category;

use App\Entity\Annonces;
use App\Entity\Energie;
use App\Entity\SousCategorie;
use App\Entity\Vehicule;
use App\Form\AnnoncesType;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;

class VehiculeType extends AnnoncesType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $annonce = new Annonces();
        $options['categorie_id'] = $annonce->getCategoryId('Vehicule');
        parent::buildForm($builder, $options);
        
        $builder
            ->add('energie', EntityType::class, [
                // looks for choices from this entity
                'class' => Energie::class,
                'choice_label' => 'libelle',
            ])
            ->add('marque')
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Vehicule::class,
        ]);
    }
}
