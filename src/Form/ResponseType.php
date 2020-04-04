<?php

namespace App\Form;

use App\Entity\Response;
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

class ResponseType extends AbstractType
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
            'data_class' => Response::class,
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
