<?php

namespace App\Form;

use App\Entity\Category;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class MissionFilterType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('client', TextType::class, [
                'required' => false,
                'label' => 'Client',
                'attr' => ['placeholder' => 'Nom ou prénom'],
            ])
            ->add('freelance', TextType::class, [
                'required' => false,
                'label' => 'Freelance',
                'attr' => ['placeholder' => 'Nom ou prénom'],
            ])
            ->add('dateFrom', DateType::class, [
                'widget' => 'single_text',
                'required' => false,
                'label' => 'Du',
            ])
            ->add('dateTo', DateType::class, [
                'widget' => 'single_text',
                'required' => false,
                'label' => 'Au',
            ])
            ->add('budgetMin', NumberType::class, [
                'required' => false,
                'label' => 'Budget min (€)',
                'attr' => ['placeholder' => '0'],
            ])
            ->add('budgetMax', NumberType::class, [
                'required' => false,
                'label' => 'Budget max (€)',
                'attr' => ['placeholder' => '99999'],
            ])
            ->add('categories', EntityType::class, [
                'class' => Category::class,
                'choice_label' => 'label',
                'multiple' => true,
                'expanded' => false,
                'required' => false,
                'label' => 'Catégories',
                'attr' => ['size' => 5],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'method' => 'GET',
            'csrf_protection' => false,
        ]);
    }

    public function getBlockPrefix(): string
    {
        return '';
    }
}
