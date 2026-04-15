<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\IsTrue;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class RegistrationFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('userType', ChoiceType::class, [
                'mapped' => false,
                'label' => 'Je suis',
                'choices' => [
                    'Client' => 'ROLE_CLIENT',
                    'Freelancer' => 'ROLE_FREELANCER',
                ],
                'expanded' => true,
                'multiple' => false,
                'constraints' => [
                    new NotBlank(message: 'Veuillez choisir un type de compte'),
                ],
            ])
            ->add('firstName', TextType::class, [
                'label' => 'Prénom',
                'constraints' => [new NotBlank(message: 'Veuillez entrer votre prénom')],
            ])
            ->add('lastName', TextType::class, [
                'label' => 'Nom',
                'constraints' => [new NotBlank(message: 'Veuillez entrer votre nom')],
            ])
            ->add('email', TextType::class, [
                'label' => 'Email',
                'constraints' => [new NotBlank(message: 'Veuillez entrer votre email')],
            ])
            ->add('company', TextType::class, [
                'label' => 'Nom de l\'entreprise',
                'required' => false,
            ])
            ->add('SIRET', TextType::class, [
                'label' => 'SIRET',
                'required' => false,
                'constraints' => [
                    new Length(
                        exactly: 14,
                        exactMessage: 'Le SIRET doit contenir {{ limit }} caractères',
                    ),
                ],
            ])
            ->add('street', TextType::class, [
                'label' => 'Rue',
                'constraints' => [new NotBlank(message: 'Veuillez entrer votre rue')],
            ])
            ->add('postalCode', TextType::class, [
                'label' => 'Code postal',
                'constraints' => [new NotBlank(message: 'Veuillez entrer votre code postal')],
            ])
            ->add('city', TextType::class, [
                'label' => 'Ville',
                'constraints' => [new NotBlank(message: 'Veuillez entrer votre ville')],
            ])
            ->add('country', TextType::class, [
                'label' => 'Pays',
                'constraints' => [new NotBlank(message: 'Veuillez entrer votre pays')],
            ])
            ->add('agreeTerms', CheckboxType::class, [
                'mapped' => false,
                'constraints' => [
                    new IsTrue(
                        message: 'You should agree to our terms.',
                    ),
                ],
            ])
            ->add('plainPassword', PasswordType::class, [
                // instead of being set onto the object directly,
                // this is read and encoded in the controller
                'mapped' => false,
                'attr' => ['autocomplete' => 'off'],
                'constraints' => [
                    new NotBlank(
                        message: 'Please enter a password',
                    ),
                    new Length(
                        min: 8,
                        minMessage: 'Your password should be at least {{ limit }} characters',
                        // max length allowed by Symfony for security reasons
                        max: 4096,
                    ),
                ],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
