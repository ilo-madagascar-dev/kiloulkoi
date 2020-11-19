<?php

namespace App\Form\Category;

use App\Entity\AnnonceMeubleDeco;
use App\Entity\Annonces;
use App\Form\AnnoncesType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class MeubleDecoType extends AnnoncesType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $options['classe'] = 'MeubleDeco';
        parent::buildForm($builder, $options);

        $builder
            ->add('marque')
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => AnnonceMeubleDeco::class,
        ]);
    }
}
