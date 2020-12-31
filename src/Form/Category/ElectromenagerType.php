<?php

namespace App\Form\Category;

use App\Entity\AnnonceElectromenager;
use App\Entity\Annonces;
use App\Form\AnnoncesType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ElectromenagerType extends AnnoncesType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $options['classe'] = 'Electromenager';
        parent::buildForm($builder, $options);

        $builder
            ->add('marque')
            ->add('modele', TextType::class, ['label' => 'Modèle'])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => AnnonceElectromenager::class,
        ]);
    }
}
