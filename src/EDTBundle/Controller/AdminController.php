<?php

namespace EDTBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;

//use Symfony\Component\Security\Core\Exception\AccessDeniedException;
//use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
//use FOS\UserBundle\Entity\User;

use EDTBundle\Entity\Salle;
use EDTBundle\Entity\Matiere;
use UserBundle\Entity\Professeur;
use UserBundle\Entity\Etudiant;
use UserBundle\Form\EtudiantType;
use EDTBundle\Form\SalleType;


/**
 * @author lenoir
 * Ce controlleur a pour but d'implémenter toutes les fonctions utilisables
 * par l'admin.
 * Pour chaque entity : affichage/Ajout/modification/suppression.
 */
class AdminController extends Controller
{
    public function indexAction()
    {
        return $this->render('EDTBundle:Admin:index.html.twig');
    }


  /*
     Une fonction pour traiter l'affichage des entités du bundle EDTBundle
     Et une autre fonction pour traiter l'affichage des users.

     -La fonction afficherEntiteAction est générique et permet donc d'afficher n'importe quelle entite
     pour cela il faut récupérer les noms des attributs de l'entite et les valeurs de celle-ci.
  */
    public function afficherEntiteAction($entite){
      $em = $this->getDoctrine()->getManager();
      $nomAttributs = $em->getClassMetadata('EDTBundle\Entity\\'.$entite)->getFieldNames();
      $listeEntites = $em->getRepository('EDTBundle:'.$entite)->findAll();
      return $this->render('EDTBundle:Admin:entite_view.html.twig',
      [ 'nomAttributs' => $nomAttributs,
        'entites' => $listeEntites,
        'route_edit' => 'edt_'.$entite.'_edit',
        'route_delete'=> 'edt_entite_delete',
        'route_add' =>'edt_'.$entite.'_add',
        'nomEntite' => $entite
      ]
      );
    }

    public function ajouterSalleAction(Request $request){
      $salle = new Salle();
      $form = $this->get('form.factory')->create( SalleType::class, $salle);
      //le formulaire généré va hydrater l'objet $salle
      if ($form->handleRequest($request)->isValid()){
        $em=$this->getDoctrine()->getManager();
        $em->persist($salle);
        $em->flush();
        $request->getSession()->getFlashBag()->add('notice', 'Annonce bien enregistrée.');
        return $this->redirect($this->generateUrl('edt_entite_view', ['entite' => 'Salle']));
      }
      return $this->render('EDTBundle:Admin:addEntite.html.twig', ['form' => $form->createView()]);
    }

    public function editerSalleAction($entite, $id){
      return new Response('<body>Page pour édité l\'entité : '.$entite.' d\'id : '.$id.'</body>');

    }
    public function deleteEntiteAction($entite, $id){
      $em = $this->getDoctrine()->getManager();
      if ($entite == 'User'){
        $bundle = 'User';
      }
      else{
        $bundle = 'EDT';
      }
      $entite_a_supprimer = $em->getRepository($bundle.'Bundle:'.$entite)->find($id);
      $em->remove($entite_a_supprimer);
      $em->flush();
      return new Response('<body>Entite bien supprimee. </body>');
    }


    /* entite = Etudiant ou Professeur  edt_user_view:*/
    public function afficherUserAction($entite){
      $em = $this->getDoctrine()->getManager();
      $listeEntite = $em->getRepository('UserBundle:'.$entite)->findAll();
      return $this->render('EDTBundle:Admin:user_view.html.twig',
        ['listeUsers' => $listeEntite,
        'route_edit' => 'edt_user_edit',
        'route_delete'=> 'edt_entite_delete',
        'route_add' =>'edt_user_add',
        'nomEntite' => $entite
        ]
      );
    }

    /* fonction pour ajouter un PRofesseur ou un Etudiant
    la distinction se fait au niveau du parametre entite
    A voir si il y a moyen de fusionner les action "add, et edit de toutes les entités."*/
    public function addUserAction(Request $request, $entite){
      if ($entite == 'Etudiant'){
        $user = new Etudiant();
        $form = $this->get('form.factory')->create( EtudiantType::class, $user);

      }
      else{
        $user = new Professeur();
        $form = $this->get('form.factory')->create( ProfesseurType::class, $user);

      }
      //le formulaire généré va hydrater l'objet $salle
      if ($form->handleRequest($request)->isValid()){
        $em=$this->getDoctrine()->getManager();
        $em->persist($user);
        $em->flush();
        $request->getSession()->getFlashBag()->add('notice', 'Annonce bien enregistrée.');
        return $this->redirect($this->generateUrl('edt_user_view', ['entite' => $entite]));
      }
      return $this->render('EDTBundle:Admin/Entite:add'.$entite.'.html.twig', ['form' => $form->createView()]);
    }


    public function editUserAction(Request $request, $entite, $id){
      $em = $this->getDoctrine()->getManager();
      $etudiant = $em->getRepository('UserBundle:User')->find($id);

     return new Response('<body>Id choisi : '.$id.' LOL.</body>');
    //  return $this->render('EDTBundle:Admin:etudient_edit.html.twig', ['etudiant' => $etudiant]);
    }





}
