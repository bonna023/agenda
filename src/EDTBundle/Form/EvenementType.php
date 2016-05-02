<?php

namespace EDTBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type;
use Symfony\Component\OptionsResolver\OptionsResolver;

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
            ->add('groupes')
            ->add('salle')
            ->add('professeur')
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
