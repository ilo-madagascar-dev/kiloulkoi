<?php

namespace App\Form\Category;

use App\Entity\Annonces;
use App\Entity\AnnonceService;
use App\Form\AnnoncesType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ServiceType extends AnnoncesType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $annonce = new Annonces();
        $classe  = 'Service';
        $options['categorie_id'] = $annonce->getCategoryId($classe);
        parent::buildForm($builder, $options);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => AnnonceService::class,
        ]);
    }
}
