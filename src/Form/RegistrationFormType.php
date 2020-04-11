<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\IsTrue;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\EqualTo;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;

class RegistrationFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('email', EmailType::class)
            ->add('username')
            ->add('agreeTerms', CheckboxType::class, [
                'mapped' => false,
                'label' => "J'accepte les conditions d'utilisation",
                'constraints' => [
                    new IsTrue([
                        'message' => "Vous devez accepter les conditions d'utilisation.",
                    ]),
                ],
            ])
            ->add('plain_password', PasswordType::class, [
                // instead of being set onto the object directly,
                // this is read and encoded in the controller
                // 'mapped' => false,
                'label' => "Mot de passe",
                'constraints' => [
                    new NotBlank([
                        'message' => 'Vous devez saisir un mot de passe',
                    ]),
                    new Length([
                        'min' => 8,
                        'minMessage' => 'Votre mot de passe doit contenur au minimum {{ limit }} caractères',
                        // max length allowed by Symfony for security reasons
                        'max' => 50,
                    ]),
                ],
            ])
            ->add('confirm_password', PasswordType::class, [
                // instead of being set onto the object directly,
                // this is read and encoded in the controller
                // 'mapped' => false,
                'label' => "Confirmation du Mot de passe",
                // 'constraints' => [
                //     new EqualTo([
                //         'message' => 'Le mot de passe de confirmation doit être identique à votre mot de passe',
                //         'propertyPath' => "plainPassword"
                //     ]),
                // ],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
