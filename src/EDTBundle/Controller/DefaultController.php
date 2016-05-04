<?php

namespace EDTBundle\Controller;


use Symfony\Component\Security\Core\SecurityContext;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction()
    {
        return $this->render('EDTBundle:Default:index.html.twig');
    }

    public function a_proposAction(){
      return $this->render('EDTBundle:Default:a_propos.html.twig');
    }
    public function testAction(){




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
      $em = $this->getDoctrine()->getManager();

      /* construction de la requete */
      $query = $em->getRepository('UserBundle:Etudiant')
                        ->createQueryBuilder('a')
                        ->where('a.id = :iduser')
                        ->setParameter('iduser', $utilisateur->getId());
        /*exectuon de la requete*/
      $etudiant = $query->getQuery()->getOneOrNullResult();
      /* on ne veut qu'un seul resultat, donc pas de getResult()*/
      if($etudiant ==NULL){
          $query = $em->getRepository('UserBundle:Professeur')
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
              $companyEvents = $em->getRepository('EDTBundle:Evenement')
                ->createQueryBuilder('company_events')
                ->getQuery()->getResult();
        break;
        case 'professeur' :
          $companyEvents = $em->getRepository('EDTBundle:Evenement')
                ->createQueryBuilder('company_events')
                ->where('company_events.professeur = :prof')
                ->setParameter('prof' , $utilisateur/*->getId()*/)
                ->getQuery()->getResult();
        break;
        case 'etudiant':
          $companyEvents = $em->getRepository('EDTBundle:Evenement')
                ->createQueryBuilder('company_events')
                ->leftJoin('company_events.groupes', 'groupes')
                ->where('groupes.id = :id_groupe')
                ->setParameter('id_groupe', $etudiant->getGroupe()->getId())
                ->getQuery()->getResult();
        break;
      }

      /*    $qb = $this->createQueryBuilder('m')
          ->leftJoin('m.seances' ,'s')
          ->addSelect('s')
          //->where('s.nbHeures > 0') inutile car on ne stockera que les seances ou il y a des heures
          // exemple => une matière sans Tp, n'aura pas de séance tp à 0 heure dans la BDD.
          //si il n y a rien on en déduit que le nombre d'heure est 0.
          ->leftJoin('s.type', 't')
          ->addSelect('t');
          return $qb->getQuery()->getResult();
    */

      dump($companyEvents);
      die;


    }
}
