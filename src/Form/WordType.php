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
use Symfony\Component\Form\Extension\Core\Type\CollectionType;

class WordType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name')
            ->add('description')
            ->add('category', CollectionType::class, array(
                'entry_type'   => CategoryType::class,
                'allow_add'    => true
            ))
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
