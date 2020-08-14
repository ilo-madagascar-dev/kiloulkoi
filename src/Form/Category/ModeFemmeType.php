<?php

namespace App\Form\Category;

use App\Entity\AnnonceModeFemme;
use App\Entity\Annonces;
use App\Entity\Propriete;
use App\Form\AnnoncesType;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ModeFemmeType extends AnnoncesType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $annonce = new Annonces();
        $classe  = 'ModeFemme';
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
            'data_class' => AnnonceModeFemme::class,
        ]);
    }
}
