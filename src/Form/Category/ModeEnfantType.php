<?php

namespace App\Form\Category;

use App\Entity\AnnonceModeEnfant;
use App\Entity\Annonces;
use App\Entity\Taille;
use App\Entity\TailleEnfant;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use App\Form\AnnoncesType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ModeEnfantType extends AnnoncesType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $options['classe'] = 'ModeEnfant';
        parent::buildForm($builder, $options);

        $builder
            ->add('marque')
            ->add('modele', TextType::class, ['label' => 'Modèle'])
            ->add('pointure',
                IntegerType::class,
                array(
                    'attr' => array(
                        'min' => 16,
                        'max' => 36,
                        'class' => 'pointure'
                    ),
                )
            )
            ->add('taille', EntityType::class, [
                'class' => TailleEnfant::class,
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
            'data_class' => AnnonceModeEnfant::class,
        ]);
    }
}
