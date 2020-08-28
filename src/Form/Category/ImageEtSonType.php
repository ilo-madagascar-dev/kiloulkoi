<?php

namespace App\Form\Category;

use App\Entity\AnnonceImageEtSon;
use App\Entity\Annonces;
use App\Form\AnnoncesType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ImageEtSonType extends AnnoncesType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $options['classe'] = 'ImageEtSon';
        parent::buildForm($builder, $options);

        $builder
            ->add('marque')
            ->add('modele')
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => AnnonceImageEtSon::class,
        ]);
    }
}
