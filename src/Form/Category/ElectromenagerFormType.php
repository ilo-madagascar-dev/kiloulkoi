<?php

namespace App\Form\Category;

use App\Entity\AnnonceElectromenager;
use App\Entity\Annonces;
use App\Form\AnnoncesType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ElectromenagerFormType extends AnnoncesType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $annonce = new Annonces();
        $classe  = 'Electromenager';
        $options['categorie_id'] = $annonce->getCategoryId($classe);
        parent::buildForm($builder, $options);

        $builder
            ->add('marque')
            ->add('modele')
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => AnnonceElectromenager::class,
        ]);
    }
}
