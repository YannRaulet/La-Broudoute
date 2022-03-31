<?php

namespace App\Form;

use App\Entity\Address;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TelType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\CountryType;

class AddressType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class, [
                'label' => 'Quel nom souhaitez-vous donner à votre adresse',
                'attr' => [
                    'placeholder' => 'Votre adresse'
                ]
            ])
            ->add('firstname'
            , TextType::class, [
                'label' => 'Prénom',
                'attr' => [
                    'placeholder' => 'Entrez votre prénom'
                ]
            ])
            ->add('lastname', TextType::class, [
                'label' => 'Nom',
                'attr' => [
                    'placeholder' => 'Entrez votre nom'
                ]
            ])
            ->add('company', TextType::class, [
                'label' => 'Votre société (facultatif)',
                'required' =>false,
                'attr' => [
                    'placeholder' => 'Entrez le nom de votre société'
                ]
            ])
            ->add('address', TextType::class, [
                'label' => 'Adresse',
                'attr' => [
                    'placeholder' => '8 rue des roses ...'
                ]
            ])
            ->add('postal', TextType::class, [
                'label' => 'Code postale',
                'attr' => [
                    'placeholder' => 'Entrez votre code postale'
                ]
            ])
            ->add('city', TextType::class, [
                'label' => 'Ville',
                'attr' => [
                    'placeholder' => 'Entrez votre ville'
                ]
            ])
            ->add('country', CountryType::class, [
                'label' => 'Pays',
                'attr' => [
                    'placeholder' => 'Entrez votre pays'
                ]
            ])
            ->add('phone', TelType::class, [
                'label' => 'Votre numéro de téléphone',
                'attr' => [
                    'placeholder' => 'Entrez votre numéro de téléphone'
                ]
            ])
            ->add('submit', SubmitType::class, [
                'label' => 'Valider mon adresse',
                'attr' =>[
                    'class' => 'btn button-design'
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Address::class,
        ]);
    }
}
