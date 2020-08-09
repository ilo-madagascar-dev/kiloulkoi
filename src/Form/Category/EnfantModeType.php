<?php

namespace App\Form\Category;

use App\Entity\Annonces;
use App\Entity\Categories;
use App\Entity\EnfantMode;
use App\Entity\Pointure;
use App\Entity\Taille;
use App\Entity\User;
use App\Form\AnnoncesType;
use Doctrine\ORM\EntityRepository;
use Shapecode\Bundle\HiddenEntityTypeBundle\Form\Type\HiddenEntityType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class EnfantModeType extends AnnoncesType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $annonce = new Annonces();
        $classe  = 'EnfantMode';
        $options['categorie_id'] = $annonce->getCategoryId($classe);
        parent::buildForm($builder, $options);

        $builder
            ->add('pointure', EntityType::class, [
                'class' => Pointure::class,
                'query_builder' => function (EntityRepository $er) {
                    $classe = 'EnfantMode';
                    return $er->createQueryBuilder('p')
                        ->where('p.classe = :classe')
                        ->setParameter('classe', $classe);
                },
                'choice_label' => 'libelle'])
            ->add('taille', EntityType::class, [
                'class' => Taille::class,
                'query_builder' => function (EntityRepository $er) {
                    $classe = 'EnfantMode';
                    return $er->createQueryBuilder('t')
                        ->where('t.classe = :classe')
                        ->setParameter('classe', $classe);
                },
                'choice_label' => 'libelle'])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => EnfantMode::class,
        ]);
    }
}
