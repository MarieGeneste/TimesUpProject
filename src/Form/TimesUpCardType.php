<?php

namespace App\Form;

use App\Entity\TimesUpCard;
use App\Entity\Response;
use App\Entity\Edition;
use App\Form\BlueCardType;
use App\Form\YellowCardType;
use App\Repository\ResponseRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;

class TimesUpCardType extends AbstractType
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
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => TimesUpCard::class,
            'translation_domain' => 'forms'
        ]);
    }
}
