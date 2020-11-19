<?php

namespace App\Form\Category;

use App\Entity\AnnonceMaternite;
use App\Entity\Annonces;
use App\Entity\TailleMaternite;
use App\Form\AnnoncesType;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
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
            ->add('pointure',  
                IntegerType::class, 
                array(
                    'attr' => array(
                        'min' => 12, 
                        'max' => 24,
                        'class' => 'pointure'
                    ),
                )
            )
            ->add('taille', EntityType::class, [
                'class' => TailleMaternite::class,
                'query_builder' => function (EntityRepository $er) 
                {
                    return $er->createQueryBuilder('t')
                            ->orderBy('t.id');
                },
                'choice_label' => 'libelle'
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => AnnonceMaternite::class,
        ]);
    }
}
