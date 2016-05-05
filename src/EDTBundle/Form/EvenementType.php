<?php

namespace EDTBundle\Form;

use Doctrine\ORM\EntityManager;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
/*use EDTBundle\Repository\SalleRepository;*/
use Symfony\Component\Form\Extension\Core\Type;
/*use Symfony\Componenet\Form\Extension\Core\*/
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormInterface;
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
            ->add('matiere', EntityType::class,            /*en 1er choisir la matiere */
                ['class' => 'EDTBundle:Matiere',
                 'choice_label' => 'nom',
                 'multiple' => false])

            ->add('groupes',EntityType::class,
                ['class' => 'EDTBundle:Groupe',
                 'choice_label' =>'nom',
                 'multiple' =>true
               ])
               /*
            ->add('type', EntityType::class,
                ['class' => 'EDTBundle:Type',
                  'choice_label' => 'nom',
                  'multiple' =>false ])
                  */

            ->add('professeur', EntityType::class,
                ['class' => 'UserBundle:Professeur',
                  'choice_label' => 'username',
                  'multiple' => false])
            ->add('startDatetime', Type\DateTimeType::class,
                ['required' => false,
                 'widget' =>'single_text',
                 'format' =>'dd/MM/yyyy HH:mm'])
           ->add('endDatetime', Type\DateTimeType::class,
               ['required' => false,
                'widget' =>'single_text',
                'format' =>'dd/MM/yyyy HH:mm'
               ])
            ->add('ajouter' , Type\SubmitType::class)
        ;

        //Ajout des listeners
        /* association d'un événement à une fonction */
        $builder->addEventListener(FormEvents::PRE_SET_DATA, array($this, 'onePreSetData'));
        $builder->addEventListener(FormEvents::PRE_SUBMIT, array($this, 'onPreSubmit'));
        // de base tous les attributs sont required => true

    }
    protected function addElements(FormInterface $form, Type $type =null){
      /* retirer le bouton de fin pour l'ajouter plus tard */
        $submit =$form->get('ajouter');
        $form->remove('ajouter');
        //ajout du type de l'evenement
        $form->add('type', EntityType::class , [
          'class' => 'EDTBundle:Type',
           'choice_label' =>'nom',
           'empty_value' => '-- choisir un type --',
           'mapped' => false,
           'data' => $type
        ]);
/*
        $form->add('professeur', EntityType::class,
            ['class' => 'UserBundle:Professeur',
              'choice_label' => 'username',
              'empty_value' => '--choisir une matière en premier --',
              'mapped' => false,
              'multiple' => false])*/


        /* en fonction du type */
        $salles = array();
        if($type){
          //si le type n'est pas NULL
          // on va chercher les salles qui correspondent à ce type.
          $repo= $this->em->getRepository('EDTBundle:Salle');
          $salles=$repo->findByType($type, array('numSalle' => 'asc'));
        }

        $form->add('salle' ,EntityType::class,
            [ 'empty_value' => '--choisir un type en premier --',
              'class' => 'EDTBundle:Salle',
              'choice_label' => 'numSalle',
              'choices' => $salles,
              'multiple' => false]
            );
          $form->add($submit);
    }

/* fonction appelée avant la validation du formulaire*/
    function onPreSubmit(FormEvent $event){
      $form = $event->getForm();
      $data = $event->getData();
      /* obtention des données du formulaire en cours*/
      /* les données du formulaire ne sont pas encore,
      "hydrater" dans l'objet Evenement */

      /* recherche du type saisie par l'utilisateur pour l'ajouter
       aux données du formulaire*/
      $type = $this->em->getRepository('EDTBundle:Type')->find($data['type']);
      /* ajout dans le formulaire, pour quil puisse par la suite saisir la salle*/

      $this->addElements($form, $type);
    }

    /* fonction appelé avant l'envoi des données dans l'entité à hydrater */
    function onePreSetData(FormEvent $event){
      $evenement = $event->getData();
      $form = $event->getForm();
      $type = $evenement->getType() ? $evenement->getSalle()->getType() : null;
      $this->addElements($form, $type);
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
