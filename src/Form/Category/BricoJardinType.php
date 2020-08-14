<?php

namespace App\Form\Category;

use App\Entity\AnnonceBricoJardin;
use App\Entity\Annonces;
use App\Entity\Propriete;
use App\Form\AnnoncesType;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class BricoJardinType extends AnnoncesType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $annonce = new Annonces();
        $classe  = 'BricoJardin';
        $options['categorie_id'] = $annonce->getCategoryId($classe);
        parent::buildForm($builder, $options);

        $builder
            ->add('marque')
            ->add('modele')
            ->add('energie', EntityType::class, [
                'class' => Propriete::class,
                'query_builder' => function (EntityRepository $er) 
                {
                    return $er->createQueryBuilder('p')
                        ->where('p.libelle = :libelle')
                        ->setParameter('libelle', 'energie');
                },
                'choice_label' => 'valeur'
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => AnnonceBricoJardin::class,
        ]);
    }
}
