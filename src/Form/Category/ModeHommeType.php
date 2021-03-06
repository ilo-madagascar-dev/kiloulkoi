<?php

namespace App\Form\Category;

use App\Entity\AnnonceModeHomme;
use App\Entity\Annonces;
use App\Form\AnnoncesType;
use App\Entity\Propriete;
use App\Entity\Taille;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ModeHommeType extends AnnoncesType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $options['classe'] = 'ModeHomme';
        parent::buildForm($builder, $options);

        $builder
            ->add('marque')
            ->add('modele', TextType::class, ['label' => 'Modèle'])
            ->add('pointure',
                IntegerType::class,
                array(
                    'attr' => array(
                        'min' => 35,
                        'max' => 50,
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
            'data_class' => AnnonceModeHomme::class,
        ]);
    }
}
