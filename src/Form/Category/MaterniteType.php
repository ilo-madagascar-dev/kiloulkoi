<?php

namespace App\Form\Category;

use App\Entity\AnnonceMaternite;
use App\Entity\Annonces;
use App\Form\AnnoncesType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class MaterniteType extends AnnoncesType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $options['classe'] = 'Maternite';
        parent::buildForm($builder, $options);

        $builder
            ->add('marque')
            ->add('pointure', IntegerType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => AnnonceMaternite::class,
        ]);
    }
}
