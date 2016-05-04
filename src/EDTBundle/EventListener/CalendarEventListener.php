<?php
namespace EDTBundle\EventListener;

use ADesigns\CalendarBundle\Event\CalendarEvent;
use ADesigns\CalendarBundle\Entity\EventEntity;
/*use EDTBUndle\Entity\Evenement as EventEntity;*/
use Symfony\Component\Security\Core\SecurityContext;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManager;
use Symfony\Component\DependencyInjection\ContainerInterface as Container;

class CalendarEventListener
{
    private $entityManager;
    private $container;

    public function __construct(EntityManager $entityManager, Container $container)
    {
        $this->entityManager = $entityManager;
        $this->container = $container;
    }

    public function loadEvents(CalendarEvent $calendarEvent)
    {
        $startDate = $calendarEvent->getStartDatetime();
        $endDate = $calendarEvent->getEndDatetime();

        // The original request so you can get filters from the calendar
        // Use the filter in your query for example

        $request = $calendarEvent->getRequest();
        $filter = $request->get('filter');


        // load events using your custom logic here,
        // for instance, retrieving events from a repository



        /*=================================================*/
        /* I) Détermine quel type d'utilisateur est connecté*/
        /* Pourrait être remplacé par $utilisateur->getType()
        Mais impossible car il type n'est pas un attribut de la
        BDD; c'est uniquement un champs dans la BDD pour déterminer
        qui est User,Etudiant ou Prof...
        /*=================================================*/
        /* recuperation de l'utlilisateur actuelle */
        $utilisateur= $this->container->get('security.context')->getToken()->getUser();
        /* l'entite manager, pour intérgair avec la BDD */
        /* construction de la requete */
        $query = $this->entityManager->getRepository('UserBundle:Etudiant')
                          ->createQueryBuilder('a')
                          ->where('a.id = :iduser')
                          ->setParameter('iduser', $utilisateur->getId());
          /*exectuon de la requete*/
        $etudiant = $query->getQuery()->getOneOrNullResult();
        /* on ne veut qu'un seul resultat, donc pas de getResult()*/
        if($etudiant ==NULL){
            $query = $this->entityManager->getRepository('UserBundle:Professeur')
                            ->createQueryBuilder('a')
                            ->where('a.id = :iduser')
                            ->setParameter('iduser', $utilisateur->getId());
            $professeur = $query->getQuery()->getOneOrNullResult();
            if ($professeur ==NULL){
              $type = 'user';
            }
            else{
              $type = 'professeur';
            }
        }
        else{
          $type = 'etudiant';
        }
        /*====================================================================*/
        /* II) Recherche des evenements en fonction du type de l'utilisateur */
        /*====================================================================*/
        switch($type){
          case 'user':
                $companyEvents = $this->entityManager->getRepository('EDTBundle:Evenement')
                  ->createQueryBuilder('company_events')
                  ->getQuery()->getResult();
          break;
          case 'professeur' :
            $companyEvents = $this->entityManager->getRepository('EDTBundle:Evenement')
                  ->createQueryBuilder('company_events')
                  ->where('company_events.professeur = :prof')
                  ->setParameter('prof' , $utilisateur/*->getId()*/)
                  ->getQuery()->getResult();
          break;
          case 'etudiant':
            $companyEvents = $this->entityManager->getRepository('EDTBundle:Evenement')
                  ->createQueryBuilder('company_events')
                  ->leftJoin('company_events.groupes', 'groupes')
                  ->where('groupes.id = :id_groupe')
                  ->setParameter('id_groupe', $etudiant->getGroupe()->getId())
                  ->getQuery()->getResult();
          break;
        }

        // $companyEvents and $companyEvent in this example
        // represent entities from your database, NOT instances of EventEntity
        // within this bundle.
        //
        // Create EventEntity instances and populate it's properties with data
        // from your own entities/database values.

        foreach($companyEvents as $companyEvent) {

            // create an event with a start/end time, or an all day event
            if ($companyEvent->getAllDayEvent() === false) {
                $eventEntity = new EventEntity($companyEvent->getTitle(), $companyEvent->getStartDatetime(), $companyEvent->getEndDatetime());
            } else {
                $eventEntity = new EventEntity($companyEvent->getTitle(), $companyEvent->getStartDatetime(), null, true);
            }



            //optional calendar event settings
            //$eventEntity->setAllDay(true); // default is false, set to true if this is an all day event
            $eventEntity->setBgColor('#0000FF'); //set the background color of the event's label
            $eventEntity->setFgColor('#FFFFFF'); //set the foreground color of the event's label
            $eventEntity->setUrl('http://www.google.com'); // url to send user to when event label is clicked
            //$eventEntity->setCssClass('my-custom-class'); // a custom class you may want to apply to event labels

            //finally, add the event to the CalendarEvent for displaying on the calendar
            $calendarEvent->addEvent($eventEntity);
        }
    }
}
