<?php

namespace App\Form;

use App\Entity\OffreMission;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CandidacyFilterType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('offre', EntityType::class, [
                'class' => OffreMission::class,
                'choices' => $options['offres'],
                'choice_label' => 'title',
                'placeholder' => 'Toutes les offres',
                'required' => false,
                'label' => 'Offre',
            ])
            ->add('status', ChoiceType::class, [
                'choices' => [
                    'En attente' => 'PENDING',
                    'Acceptée'   => 'ACCEPTED',
                    'Refusée'    => 'REFUSED',
                ],
                'placeholder' => 'Tous les statuts',
                'required' => false,
                'label' => 'Statut',
            ])
            ->add('dateFrom', DateType::class, [
                'widget' => 'single_text',
                'required' => false,
                'label' => 'Depuis le',
            ])
            ->add('freelance', TextType::class, [
                'required' => false,
                'label' => 'Freelance',
                'attr' => ['placeholder' => 'Nom ou prénom'],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'method' => 'GET',
            'csrf_protection' => false,
            'offres' => [],
        ]);
    }

    public function getBlockPrefix(): string
    {
        return '';
    }
}
