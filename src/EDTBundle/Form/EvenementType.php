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
use EDTBundle\Entity\Type as TypeCours;
use EDTBUndle\Entity\Matiere;
use UserBundle\Entity\Professeur;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;

class EvenementType extends AbstractType
{
  protected $em;

  function __construct(EntityManager $em){
    $this->em = $em;
  }


    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title')
            ->add('groupes',EntityType::class,
                ['class' => 'EDTBundle:Groupe',
                 'choice_label' =>'nom',
                 'multiple' =>true
               ])
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
    protected function addElements(FormInterface $form, TypeCours $type =null){
      /* retirer le bouton de fin pour l'ajouter plus tard */
        $submit =$form->get('ajouter');
        $form->remove('ajouter');
        //ajout du type de l'evenement
        $form->add('type', EntityType::class , [
          'class' => 'EDTBundle:Type',
           'choice_label' =>'nom',
           'placeholder' => '-- choisir un type --',
           'mapped' => false,
           'data' => $type
        ]);
        echo 'dans addElements.<br />';
        dump($type);die;

        /* en fonction du type */
        $salles = array();
        if($type){
          //si le type n'est pas NULL
          // on va chercher les salles qui correspondent à ce type.
          $repo= $this->em->getRepository('EDTBundle:Salle');
          $salles=$repo->createQueryBuilder('salles')
                      ->leftJoin('salles.type', 't')
                      ->addSelect('t')
                      ->where('t.id = :type_id')
                      ->setParameter('type_id', $type->getId())
                      ->getQuery()->getResult();
            /*dump($salles);die;*/
          /*findByType($type, array('numSalle' => 'asc'));*/

        }

        $form->add('salle' ,EntityType::class,
            [ 'placeholder' => '--choisir un type en premier --',
              'class' => 'EDTBundle:Salle',
              'choice_label' => 'numSalle',
              'choices' => $salles,
              'multiple' => false]
            );
          $form->add($submit);
    }
    public function addElements_Prof_Matiere(FormInterface $form, Matiere $matiere =null){
      $submit = $form->get('ajouter');
      $form->remove('ajouter');
      $form->add('matiere', EntityType::class,
          ['class' => 'EDTBundle:Matiere',
           'choice_label' => 'nom',
           'placeholder' => '-- choisir une matière --',
           'multiple' => false,
           'mapped' =>false,
           'data' => $matiere
         ]);

         $profs = array();
         if($matiere){
           $repo = $this->em->getRepository('UserBundle:Professeur');
              $profs= $repo->createQueryBuilder('p')
              ->leftJoin('p.prof_matieres', 'pm')
              ->addSelect('pm')
              ->leftJoin('pm.matiere', 'm')
              ->addSelect('m')
              ->where('m.id = :id_m')
              ->setParameter('id_m' ,$matiere->getId())
              ->getQuery()->getResult();
         }
         $form->add('professeur', EntityType::class,
          ['class' => 'UserBundle:Professeur',
            'choice_label' => 'username',
            'placeholder' => '--choisir une matière en premier --',
            'choices' =>$profs,
            'multiple' => false]);
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
      $type = $this->em->getRepository('EDTBundle:Type')->findOneById($data['type']);
      /* ajout dans le formulaire, pour quil puisse par la suite saisir la salle*/
      /*echo 'dans preSubmit.<br />';
      dump($type);die;*/
      $matiere = $this->em->getRepository('EDTBundle:Matiere')->findOneById($data['matiere']);

      $this->addElements($form, $type);
      $this->addElements_Prof_Matiere($form, $matiere);


    }

    /* fonction appelé avant l'envoi des données dans l'entité à hydrater */
    function onePreSetData(FormEvent $event){

      $evenement = $event->getData();
      $form = $event->getForm();
     /*dump($event);
      dump($evenement->getSalle());
      die;*/
      $type = $evenement->getType() ? $evenement->getSalle()->getType() : null;
      $matiere = $evenement->getMatiere() ? $evenement->getMatiere(): null;

      $this->addElements($form, $type);
      $this->addElements_Prof_Matiere($form, $matiere);
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
