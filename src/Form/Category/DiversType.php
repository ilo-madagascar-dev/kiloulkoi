<?php

namespace App\Form\Category;

use App\Entity\AnnonceDivers;
use App\Entity\Annonces;
use App\Form\AnnoncesType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class DiversType extends AnnoncesType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $annonce = new Annonces();
        $classe  = 'Divers';
        $options['categorie_id'] = $annonce->getCategoryId($classe);
        parent::buildForm($builder, $options);

        // $builder
        //     ->add('sousCategorie')
        // ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => AnnonceDivers::class,
        ]);
    }
}
