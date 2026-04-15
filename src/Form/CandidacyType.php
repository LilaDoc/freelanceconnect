<?php

namespace App\Form;

use App\Entity\Candidacy;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Url;

class CandidacyType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('motivationMessage', TextareaType::class, [
                'label' => 'Message de motivation',
                'attr' => [
                    'rows' => 6,
                    'placeholder' => 'Expliquez pourquoi vous êtes le candidat idéal pour cette mission...',
                ],
                'constraints' => [
                    new NotBlank(['message' => 'Veuillez rédiger un message de motivation.']),
                    new Length([
                        'min' => 50,
                        'max' => 5000,
                        'minMessage' => 'Votre message doit contenir au moins {{ limit }} caractères.',
                        'maxMessage' => 'Votre message ne peut pas dépasser {{ limit }} caractères.',
                    ]),
                ],
            ])
            ->add('cv', FileType::class, [
                'label' => 'CV (PDF)',
                'mapped' => false,
                'required' => true,
                'constraints' => [
                    new NotBlank(['message' => 'Veuillez télécharger votre CV.']),
                    new File([
                        'maxSize' => '5M',
                        'mimeTypes' => ['application/pdf'],
                        'mimeTypesMessage' => 'Veuillez télécharger un fichier PDF valide.',
                        'maxSizeMessage' => 'Le fichier est trop volumineux ({{ size }} {{ suffix }}). Maximum autorisé : {{ limit }} {{ suffix }}.',
                    ]),
                ],
            ])
            ->add('projectLink', UrlType::class, [
                'label' => 'Lien vers un projet (portfolio, GitHub, etc.)',
                'required' => true,
                'attr' => [
                    'placeholder' => 'https://github.com/votre-projet',
                ],
                'constraints' => [
                    new NotBlank(['message' => 'Veuillez fournir un lien vers un projet.']),
                    new Length([
                        'max' => 500,
                        'maxMessage' => 'Le lien ne peut pas dépasser {{ limit }} caractères.',
                    ]),
                    new Url(['message' => 'Veuillez entrer une URL valide.']),
                ],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Candidacy::class,
        ]);
    }
}
