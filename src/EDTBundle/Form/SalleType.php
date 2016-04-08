<?php

namespace EDTBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type;
use EDTBundle\Form\TypeType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
class SalleType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('numSalle')
            ->add('numBatiment')
            ->add('capacite')
            ->add('type',EntityType::class,
                ['class' => 'EDTBundle:Type',
                 'choice_label' =>'type',
                 'multiple' =>false
                ]
            )
            ->add('save' , Type\SubmitType::class)

        ;
        // de base tous les attributs sont required => true
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'EDTBundle\Entity\Salle'
        ));
    }
}
