<?php

namespace App\Form\Category;

use App\Entity\AnnonceModeHomme;
use App\Entity\Annonces;
use App\Form\AnnoncesType;
use App\Entity\Propriete;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ModeHommeType extends AnnoncesType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $annonce = new Annonces();
        $classe  = 'ModeHomme';
        $options['categorie_id'] = $annonce->getCategoryId($classe);
        parent::buildForm($builder, $options);

        $builder
            ->add('marque')
            ->add('modele')
            ->add('pointure')
            ->add('taille', EntityType::class, [
                'class' => Propriete::class,
                'query_builder' => function (EntityRepository $er) 
                {
                    return $er->createQueryBuilder('p')
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
