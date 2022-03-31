<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\IsTrue;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;

class RegisterType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('firstname', TextType::class, [
                'label' => 'Votre prénom',
                'attr' => [
                    'placeholder' => 'Entrez votre prénom'
                ],
                'constraints' => new Length([
                    'min' => 2,
                    'minMessage' => 'Votre prénom doit faire au minimum {{ limit }} caractères',
                    // max length allowed by Symfony for security reasons
                    'max' => 30,
                ]),
            ])
            ->add('lastname', TextType::class, [
                'label' => 'Votre nom',
                'attr' => [
                    'placeholder' => 'Entrez votre nom'
                ],
                'constraints' => new Length([
                    'min' => 2,
                    'minMessage' => 'Votre nom doit faire au minimum {{ limit }} caractères',
                    // max length allowed by Symfony for security reasons
                    'max' => 30,
                ]),
            ])
            ->add('email', EmailType::class, [
                'label' => 'votre email',
                'attr' => [
                    'placeholder' => 'Entrez votre adresse mail'
                ]
            ])
            ->add('password', RepeatedType::class, [
                'type' => PasswordType::class,
                'invalid_message' => 'Le mot de passe et la confirmation doivent être identiques',
                'label' => 'Votre mot de passe',
                'required' => true,
                'first_options' => [ 
                    'label' => 'Mot de passe',
                    'attr' => [
                        'placeholder' => "Merci de saisir votre mot de passe."
                    ]
                ],
                'second_options' => [ 
                    'label' => 'Confirmez votre mot de passe',
                    'attr' => [
                        'placeholder' => "Merci de confirmer votre mot de passe."
                    ]    
                ],
                'constraints' => [
                    new NotBlank([
                        'message' => 'Merci de saisir un mot de passe',
                    ]),
                    new Length([
                        'min' => 6,
                        'minMessage' => 'Votre mot de passe doit faire au minimum {{ limit }} caractères',
                        // max length allowed by Symfony for security reasons
                        'max' => 4096,
                    ]),
                ],
            ])
            ->add('agreeTerms', CheckboxType::class, [
                'mapped' => false,
                'label' => "En cochant cette case, vous acceptez notre politique de confidentialité (voir : Mentions légales)",
                'constraints' => [
                    new IsTrue([
                        'message' => 'Vous devez accepter les conditions.',
                    ]),
                ],
            ])
            ->add('submit', SubmitType::class, [
                'label' => 'Créer mon compte',
                'attr' => [
                    'class' => 'btn button-design'
                ]
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
