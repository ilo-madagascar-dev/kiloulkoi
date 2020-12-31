<?php

namespace App\Form\Category;

use App\Entity\AnnonceModeFemme;
use App\Entity\Annonces;
use App\Entity\Taille;
use App\Form\AnnoncesType;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ModeFemmeType extends AnnoncesType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $options['classe'] = 'ModeFemme';
        parent::buildForm($builder, $options);

        $builder
            ->add('marque')
            ->add('modele', TextType::class, ['label' => 'Modèle'])
            ->add('pointure',
                IntegerType::class,
                array(
                    'attr' => array(
                        'min' => 35,
                        'max' => 40,
                        'class' => 'pointure'
                    ),
                )
            )
            ->add('taille', EntityType::class, [
                'class' => Taille::class,
                'query_builder' => function (EntityRepository $er)
                {
                    return $er->createQueryBuilder('p')
                        ->orderBy('p.id')
                        ->where('p.libelle = :libelle')
                        ->setParameter('libelle', 'taille');
                },
                'choice_label' => 'valeur'
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => AnnonceModeFemme::class,
        ]);
    }
}
