<?php

namespace App\Form\Category;

use App\Entity\AnnonceMultimedia;
use App\Entity\Annonces;
use App\Form\AnnoncesType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class MultimediaType extends AnnoncesType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $annonce = new Annonces();
        $classe  = 'Multimedia';
        $options['categorie_id'] = $annonce->getCategoryId($classe);
        parent::buildForm($builder, $options);

        $builder
            ->add('systeme')
            ->add('marque')
            ->add('couleur')
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => AnnonceMultimedia::class,
        ]);
    }
}
