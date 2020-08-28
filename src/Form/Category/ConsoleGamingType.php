<?php

namespace App\Form\Category;

use App\Entity\AnnonceConsoleGaming;
use App\Entity\Annonces;
use App\Form\AnnoncesType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ConsoleGamingType extends AnnoncesType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $options['classe'] = 'ConsoleGaming';
        parent::buildForm($builder, $options);

        // $builder
        //     ->add('type')
        // ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => AnnonceConsoleGaming::class,
        ]);
    }
}
