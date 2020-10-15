<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
                ->add('email', EmailType::class)
                ->add('telephone', TextType::class, ['label' => 'Téléphone'])
                ->add('genre', ChoiceType::class, [
                    'choices'  => [
                        'Homme' => 1,
                        'Femme' => 2,
                    ],
                ])
                ->add('pseudo', TextType::class)
                ->add('ville', TextType::class)
                ->add('adresse', TextType::class)
                ->add('cp', TextType::class, ['label' => 'Code postale'])
                ->add('avatar', FileType::class, [
                    'constraints' => [
                        new File([
                            'mimeTypes' => [
                                'image/*',
                            ],
                            'mimeTypesMessage' => 'Veuillez choisir une photo!',
                        ])
                    ],
                    'data_class' => null,
                    'mapped' => false
                ])
                // ->add('password', RepeatedType::class, [
                //     'type' => PasswordType::class,
                //     'invalid_message' => 'Les deux champs doivent être identiques.',
                //     'options' => ['attr' => ['class' => 'password-field']],
                //     'required' => true,
                //     'first_options'  => ['label' => 'Mot de passe'],
                //     'second_options' => ['label' => 'Confirmer votre mot de passe'],
                //     'constraints' => [
                //         new NotBlank([
                //             'message' => 'Please enter a password',
                //         ]),
                //         new Length([
                //             'min' => 6,
                //             'minMessage' => 'Entrer au moins {{ limit }} caracteres',
                //             // max length allowed by Symfony for security reasons
                //             'max' => 4096,
                //         ]),
                //     ],
                // ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
