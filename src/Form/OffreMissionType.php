<?php

namespace App\Form;

use App\Entity\Category;
use App\Entity\OffreMission;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\GreaterThan;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Positive;

class OffreMissionType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title', TextType::class, [
                'label' => 'Titre de la mission',
                'constraints' => [
                    new NotBlank(['message' => 'Le titre est obligatoire.']),
                ],
                'attr' => [
                    'placeholder' => 'Ex: Développement d\'une application web',
                ],
            ])
            ->add('description', TextareaType::class, [
                'label' => 'Description de la mission',
                'constraints' => [
                    new NotBlank(['message' => 'La description est obligatoire.']),
                ],
                'attr' => [
                    'placeholder' => 'Décrivez en détail la mission, les compétences requises...',
                    'rows' => 6,
                ],
            ])
            ->add('budget', IntegerType::class, [
                'label' => 'Budget (€)',
                'constraints' => [
                    new NotBlank(['message' => 'Le budget est obligatoire.']),
                    new Positive(['message' => 'Le budget doit être positif.']),
                ],
                'attr' => [
                    'placeholder' => 'Ex: 5000',
                    'min' => 1,
                ],
            ])
            ->add('deadline', DateType::class, [
                'label' => 'Date limite',
                'widget' => 'single_text',
                'constraints' => [
                    new NotBlank(['message' => 'La date limite est obligatoire.']),
                    new GreaterThan([
                        'value' => 'today',
                        'message' => 'La date limite doit être dans le futur.',
                    ]),
                ],
            ])
            ->add('language', TextType::class, [
                'label' => 'Langue requise',
                'required' => false,
                'attr' => [
                    'placeholder' => 'Ex: Français, Anglais',
                ],
            ])
            ->add('category', EntityType::class, [
                'label' => 'Catégorie',
                'class' => Category::class,
                'choice_label' => 'label',
                'placeholder' => '-- Sélectionnez une catégorie --',
                'constraints' => [
                    new NotBlank(['message' => 'La catégorie est obligatoire.']),
                ],
            ])
        ;
        
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => OffreMission::class,
        ]);
    }
}
