<?php

namespace App\Form;

use App\Entity\Card;
use App\Entity\Word;
use App\Entity\Edition;
use App\Repository\WordRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CardType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('edition', EntityType::class, [
                // looks for choices from this entity
                'class' => Edition::class,
                "required" => true,
            
                // uses the User.username property as the visible option string
                'choice_label' => 'title',
            
                // used to render a select box, check boxes or radios
                'multiple' => false,
                'expanded' => false,
            ])
            ->add('yellowWord', EntityType::class, [
                // looks for choices from this entity
                'class' => Word::class,
                'query_builder' => function (WordRepository $wordRep) {
                    return $wordRep->createQueryBuilder('w')
                        ->andWhere('w.color = :color')
                        ->setParameter('color', 1);
                },
                "required" => true,
            
                // uses the User.username property as the visible option string
                'choice_label' => 'name',
            
                // used to render a select box, check boxes or radios
                'multiple' => false,
                'expanded' => false,
            ])
            ->add('blueWord', EntityType::class, [
                // looks for choices from this entity
                'class' => Word::class,
                'query_builder' => function (WordRepository $wordRep) {
                    return $wordRep->createQueryBuilder('w')
                    ->andWhere('w.color = :color')
                    ->setParameter('color', 2);
                },
                "required" => true,
            
                // uses the User.username property as the visible option string
                'choice_label' => 'name',
            
                // used to render a select box, check boxes or radios
                'multiple' => false,
                'expanded' => false,
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Card::class,
        ]);
    }
}
