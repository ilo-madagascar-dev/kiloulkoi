<?php

namespace App\Form;

use App\Entity\Particulier;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ParticulierUserType extends UserType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        parent::buildForm($builder, $options);
        $builder
            ->add('nom', TextType::class)
            ->add('prenom', TextType::class)
            ->add('telephone', TextType::class, ['label' => 'Téléphone'])
            ->add('ville', TextType::class)
            ->add('rue', TextType::class)
            ->add('cp', TextType::class, ['label' => 'Code postale'])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Particulier::class,
        ]);
    }
}
