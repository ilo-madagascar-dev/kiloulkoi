<?php

namespace App\Form\Category;

use App\Entity\AnnonceMultimedia;
use App\Entity\Annonces;
use App\Form\AnnoncesType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class MultimediaType extends AnnoncesType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $options['classe'] = 'Multimedia';
        parent::buildForm($builder, $options);

        $builder
            ->add('systeme', TextType::class, ['label' => 'Système'])
            ->add('marque', TextType::class)
            ->add('couleur', TextType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => AnnonceMultimedia::class,
        ]);
    }
}
