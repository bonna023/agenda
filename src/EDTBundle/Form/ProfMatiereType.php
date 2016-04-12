<?php

namespace EDTBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use EDTBundle\Form\Matiere;

class ProfMatiereType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('responsable', Type\CheckboxType::class, ['required' =>false])
            ->add('matiere',EntityType::class,
                ['class' => 'EDTBundle:Matiere',
                 'choice_label' =>'nom',
                 'multiple' =>false
                ])
           ->add('professeur', EntityType::class,
            ['class' => 'UserBundle:Professeur',
            'choice_label' => 'username',
            'multiple' => false])
            -> add('save' , Type\SubmitType::class)
          ;
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'EDTBundle\Entity\ProfMatiere'
        ));
    }
}
