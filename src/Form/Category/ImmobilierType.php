<?php

namespace App\Form\Category;

use App\Entity\AnnonceImmobilier;
use App\Entity\Annonces;
use App\Form\AnnoncesType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ImmobilierType extends AnnoncesType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $options['classe'] = 'Immobilier';
        parent::buildForm($builder, $options);

        $builder
            ->add('surface')
            ->add('etage', IntegerType::class)
            ->add('chambre', IntegerType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => AnnonceImmobilier::class,
        ]);
    }
}
