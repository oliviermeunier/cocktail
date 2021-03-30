<?php

namespace App\Form;

use App\Entity\Category;
use App\Repository\CategoryRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CocktailSearchType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('category', EntityType::class, [
                'class' => Category::class,
                'choice_label' => 'label',
                'query_builder' => function(CategoryRepository $categoryRepository) {
                    return $categoryRepository->createQueryBuilder('c')
                                ->orderBy('c.label', 'ASC');
                },
                'label' => 'Catégorie',
                'placeholder' => 'Toutes les catégories',
                'required' => false
            ])
            ->add('created_at_min', DateType::class, [
                'widget' => 'single_text',
                'label' => 'Cocktail créé depuis',
                'input' => 'datetime_immutable'
            ])
            ->add('submit', SubmitType::class, [
                'label' => 'Filtrer'
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            // Configure your form options here
        ]);
    }
}
