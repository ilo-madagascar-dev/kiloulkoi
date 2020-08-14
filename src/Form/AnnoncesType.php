<?php

namespace App\Form;

use App\Entity\Annonces;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use App\Entity\Categories;
use App\Entity\SousCategorie;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Validator\Constraints\File;

class AnnoncesType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('titre', TextType::class);

        if( isset($options['categorie_id']) )
        {

            $builder = $builder->add('sous_categorie', EntityType::class, [
                'class' => SousCategorie::class,
                'query_builder' => function (EntityRepository $er) use($options)
                    {
                        return $er->createQueryBuilder('sc')
                                    ->where('sc.categorie = :id')
                                    ->setParameter('id', $options['categorie_id']);
                    },
                'choice_label' => 'libelle'
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
                ->add('prix')
                ->add('description', TextareaType::class)
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
