<?php

namespace App\Form;

use App\Entity\Word;
use App\Entity\Category;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class WordType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name')
            ->add('description')
            ->add('color', ChoiceType::class, [
                'choices' => [
                    "-" => null,
                    "Jaune" => 1,
                    "Bleu" => 2
                ]
            ])
            ->add('category', EntityType::class, [
                // looks for choices from this entity
                'class' => Category::class,
                "required" => false,
            
                // uses the User.username property as the visible option string
                'choice_label' => 'title',
            
                // used to render a select box, check boxes or radios
                'multiple' => true,
                'expanded' => false,
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Word::class,
        ]);
    }
}
