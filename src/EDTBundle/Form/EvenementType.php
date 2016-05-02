<?php

namespace EDTBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type;
use Symfony\Component\OptionsResolver\OptionsResolver;
use EDTBundle\Entity\Groupe;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
class EvenementType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title')
            ->add('url')
            ->add('bgColor')
            ->add('fgColor')
            ->add('cssClass')
            ->add('datetime', 'datetime')
            ->add('startDatetime', 'datetime')
            ->add('endDatetime', 'datetime')
            ->add('allDay')
            ->add('groupes',EntityType::class,
                ['class' => 'EDTBundle:Groupe',
                 'choice_label' =>'nom',
                 'multiple' =>true
                ])
            ->add('salle', EntityType::class,
                ['class' => 'EDTBundle:Salle',
                  'choice_label' => 'numSalle',
                  'multiple' => false])
            ->add('professeur', EntityType::class,
                ['class' => 'UserBundle:Professeur',
                  'choice_label' => 'username',
                  'multiple' => false])
            ->add('matiere', EntityType::class,
                ['class' => 'EDTBundle:Matiere',
                 'choice_label' => 'nom',
                 'multiple' => false])
            ->add('ajouter' , Type\SubmitType::class)
        ;
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'EDTBundle\Entity\Evenement'
        ));
    }
}
