<?php

namespace App\Form;

use App\Entity\Annonces;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use App\Entity\Categories;
use App\Entity\TypeLocation;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Validator\Constraints\File;

class AnnoncesType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('titre', TextType::class);

        if( isset($options['classe']) && !in_array($options['classe'], ['Service', 'Divers']) )
        {
            $builder = $builder->add('sous_categorie', EntityType::class, [
                'label' => 'Sous-catégorie',
                'class' => Categories::class,
                'query_builder' => function (EntityRepository $er) use($options)
                    {
                        return $er->createQueryBuilder('c')
                                    ->where('c.categorieParent is not null')
                                    ->andWhere('c.className = :classe')
                                    ->setParameter('classe', $options['classe']);
                    },
                'choice_label' => 'libelle',
                'attr' => [
                    'class' => 'sous-categorie'
                ]
            ]);
        }

        $builder->add('photo', CollectionType::class ,[
                    'entry_type' => PhotoType::class,
                    'entry_options' => ['label' => false],
                    'allow_add' => true,
                    'prototype' => true,
                    'allow_delete' => true,
                    'by_reference' => false,
                ])
                ->add('prix', IntegerType::class, [
                    'attr' => array('min' => 0)
                ])
                ->add('type', EntityType::class, [
                    'label' => 'Location par ',
                    'class' => TypeLocation::class,
                    'query_builder' => function (EntityRepository $er) use($options)
                    {
                        if( $options['classe'] == "Service" )
                        {
                            return $er->createQueryBuilder('type')
                                        ->orderBy('type.id');
                        }
                        else
                        {
                            return $er->createQueryBuilder('type')
                                        ->where('type.id > 1')
                                        ->orderBy('type.id');
                        }
                    },
                    'choice_label' => 'libelle'
                ])
                ->add('description', TextareaType::class, ['label' => 'Déscription'])
                ->add('currency'   , TextType::class,
                    [
                        'label' => 'Montant de la caution',
                        'disabled' => true,
                        'data' => '€',
                        'mapped' => false
                    ],
                )
                // ->add('location', LocationType::class, array('required' => false))
                // ->add('categorie', EntityType::class, [
                //     'class' => Categories::class,
                //     'choice_label' => 'libelle',
                // ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Annonces::class,
        ]);
    }
}
