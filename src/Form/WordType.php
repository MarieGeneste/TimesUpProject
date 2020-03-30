<?php

namespace App\Form;

use App\Entity\Word;
use App\Entity\Category;
use App\Form\CategoryType;
use App\Repository\CategoryRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class WordType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name')
            ->add('description')
            ->add('category', EntityType::class, [
                // looks for choices from this entity
                'class' => Category::class,
                // 'query_builder' => function (WordRepository $wordRep) {
                //     return $wordRep->createQueryBuilder('w')
                //         ->andWhere('w.color = :color')
                //         ->setParameter('color', 1);
                // },
                "required" => false,
            
                // uses the User.username property as the visible option string
                'choice_label' => 'title',
            
                // used to render a select box, check boxes or radios
                'multiple' => true,
                'expanded' => false,
                'attr' => ['class' => "custom-select", 'placeholder' => 'choisir une catégorie']
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Word::class,
        ]);
    }

    // public function getCategoryChoices($catRep)
    // {
    //     $categories = $catRep->getAll();
    //     $catChoices = [];
    //     foreach ($categories as $category) {
    //         $catChoices [$category->getTitle()] = $category->getId();
    //     }
    //     return $catChoices;
    // }
}
