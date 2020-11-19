<?php

namespace App\Form\Category;

use App\Entity\AnnonceImmobilier;
use App\Form\AnnoncesType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ImmobilierType extends AnnoncesType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $options['classe'] = 'Immobilier';
        parent::buildForm($builder, $options);

        $builder
            ->add('surface', NumberType::class, [
                'label' => 'Surfaces',
                'attr' => array('min' => 0)
            ])
            ->add('etage', IntegerType::class, [
                'label' => 'Etages',
                'attr' => array('min' => 0)
            ])
            ->add('chambre', IntegerType::class, [
                'label' => 'Surfaces',
                'attr' => array('min' => 0)
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => AnnonceImmobilier::class,
        ]);
    }
}
