<?php

namespace EDTBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
//use Symfony\Component\Security\Core\Exception\AccessDeniedException;
//use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
//use FOS\UserBundle\Entity\User;

use EDTBundle\Entity\Salle;
use EDTBundle\Entity\Matiere;
use UserBundle\Entity\Professeur;
use UserBundle\Entity\Etudiant;
use UserBundle\Form\EtudiantType;
use UserBundle\Form\ProfesseurType;
use EDTBundle\Form\SalleType;
use EDTBundle\Form\MatiereType;
use EDTBundle\Entity\ProfMatiere;
use EDTBundle\Form\ProfMatiereType;
use EDTBundle\Entity\Groupe;
use EDTBundle\Form\GroupeType;
use EDTBundle\Entity\Evenement;
use EDTBundle\Form\EvenementType;
use EDTBundle\Entity\Type as TypeCours;
use EDTBundle\Form\TypeType;


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
  //   dump($nomAttributs); die;
/*    if ($entite == 'Evenement')
    {
        return $this->redirectToRoute('edt_entite_add',
              ['entite' => 'Evenement']
            );
    }*/
      return $this->render('EDTBundle:Admin:entite_view.html.twig',
      [ 'nomAttributs' => $nomAttributs,
        'entites' => $listeEntites,
        'route_edit' => 'edt_entite_edit',
        'route_delete'=> 'edt_entite_delete',
        'route_add' =>'edt_entite_add',
        'nomEntite' => $entite
      ]
      );
    }

    public function afficherProfMatiereAction(){
      $em = $this->getDoctrine()->getManager();
      $nomProfMatieres= $em->getRepository('EDTBundle:ProfMatiere')->getNomsProfsMatieres();
      //dump($nomProfMatieres); die;
//      $nomMatieres = $em->getRepository('EDTBundle:ProfMatiere')->getNomsMatieres();
    //return new Response('<body>'.$nomProfMatieres[0][0]->getId().'</body>');
     return $this->render('EDTBundle:Admin/Entite:profmatiere_view.html.twig',
        ['noms' => $nomProfMatieres,
        'route_edit' => 'edt_entite_edit',
        'route_delete'=> 'edt_entite_delete',
        'route_add' =>'edt_entite_add',
        'nomEntite' => 'ProfMatiere'
      ]);
    }

    public function afficherMatiereAction(){
      $em = $this->getDoctrine()->getManager();
      $matieres = $em->getRepository('EDTBundle:Matiere')->getMatieresSeances();
      $nomsType = $em->getRepository('EDTBundle:Type')->findAll();
      return $this->render('EDTBundle:Admin/Entite:matiere_view.html.twig',
      [
        'matieres' => $matieres,
        'nomsType' =>$nomsType,
        'route_edit' =>'edt_entite_edit',
        'route_delete' =>'edt_entite_delete',
        'route_add' => 'edt_entite_add',
        'nomEntite' => 'Matiere'
      ]);
    }


    //AVOIR!!!  si une methode genre attr dans twig///

    public function addEntiteAction(Request $request, $entite){

      $formFactory = $this->get('form.factory');
      /*$class = 'EDTBundle\Entity\\'.$entite;
      $classType = 'EDTBundle\Form\\'.$entite.'Type::class'.
    //  dump(new $class());die;
      $objet = new $class();
      $form = $formFactory->create( $classType, $objet);
*/
      switch ($entite){
        case 'Matiere':
          $objet = new Matiere();
          $form = $formFactory->create (MatiereType::class, $objet);
        break;
        case 'Salle' :
          $objet = new Salle();
          $form = $formFactory->create (SalleType::class, $objet);
        break;
        case 'ProfMatiere':
          $objet = new ProfMatiere();
          $form = $formFactory->create(ProfMatiereType::class, $objet);
        break;
        case 'Type':
          $objet =new TypeCours();
          $form = $formFactory->create(TypeType::class, $objet);
        break;
        /*
        case 'Groupe':
          $objet= new Groupe();
          $form = $formFactory->create(GroupeType::class, $objet);
          */
       /* case 'Evenement':
          $objet = new Evenement();
          $form = $formFactory->create(EvenementType::class, $objet);
        break;*/
      }
      //le formulaire généré va hydrater l'objet $salle
      if ($form->handleRequest($request)->isValid()){
        $em=$this->getDoctrine()->getManager();
        $em->persist($objet);
        $em->flush();
        $request->getSession()->getFlashBag()->add('notice', 'Annonce bien enregistrée.');
        return $this->redirect($this->generateUrl('edt_entite_view', ['entite' => $entite]));
      }
      return $this->render('EDTBundle:Admin:addEntite.html.twig', ['form' => $form->createView()]);
    }

    public function editerEntiteAction(Request $request, $entite, $id){

          $formFactory = $this->get('form.factory');
          $em = $this->getDoctrine()->getManager();
          switch ($entite){
            case 'Matiere':
              $objet = $em->getRepository('EDTBundle:Matiere')->find($id);
              $form = $formFactory->create (MatiereType::class, $objet);
            break;
            case 'Salle' :
              $objet = $em->getRepository('EDTBundle:Salle')->find($id);
              $form = $formFactory->create (SalleType::class, $objet);
            break;
            /*case 'ProfMatiere':
              $objet = $em->getRepository('EDTBundle:ProfMatiere')->find($id);
              $form = $formFactory->create(ProfMatiereType::class, $objet);
            break;*/
          }
          //le formulaire généré va hydrater l'objet $salle
          if ($form->handleRequest($request)->isValid()){
            $em=$this->getDoctrine()->getManager();
            $em->persist($objet);
            $em->flush();
            $request->getSession()->getFlashBag()->add('notice', 'Annonce bien enregistrée.');
            return $this->redirect($this->generateUrl('edt_entite_view', ['entite' => $entite]));
          }
          return $this->render('EDTBundle:Admin:addEntite.html.twig', ['form' => $form->createView()]);
    }

/* ------------------------------------------------------------------------------------------- */

    public function ajaxTypeAction(Request $request){
      if(!$request->isXmlHttpRequest()){
        throw new NotFoundHttpException();
      }
      /*obtention de l'id du type*/
      $id = $request->query->get('type_id');
      $result = array();

      /* retour de la liste des salles qui correspondent au type sélectionné*/
      $repo=$this->getDoctrine()->getManager()->getRepository('EDTBundle:Salle');
      $salles = $repo->findByType($id, ['numSalle' => 'asc']);
      foreach ($salles as $salle) {
        $result[$salle->getNumSalle()] = $salle->getId();
      }
      return new JsonResponse($result);
    }

    public function ajaxMatiereAction(Request $request){
      if(!$request->isXmlHttpRequest()){
        throw new NotFoundHttpException();
      }
      /*obtention de l'id du type*/
      $id = $request->query->get('matiere_id');
      $result = array();

      /* retour de la liste des salles qui correspondent au type sélectionné*/
      $repo=$this->getDoctrine()->getManager()->getRepository('UserBundle:Professeur');
      $professeurs = $repo->createQueryBuilder('p')
          ->leftJoin('p.prof_matieres', 'pm')
          ->leftJoin('pm.matiere', 'm')
          ->where('m.id = :id_m')
          ->setParameter('id_m' ,$id)
          ->getQuery()->getResult();

      foreach ($professeurs as $professeur) {
        $result[$professeur->getUsername()] = $professeur->getId();
      }
      return new JsonResponse($result);
    }


    /*=======================================================*/

    public function ajouterEvenementAction(Request $request){
      $evenement = new Evenement();
      $form = $this->createForm(new EvenementType($this->getDoctrine()->getManager()), $evenement);
      //le formulaire généré va hydrater l'objet $evenement
      if ($form->handleRequest($request)->isValid()){

        $em=$this->getDoctrine()->getManager();
        $titre="";
        $titre = $titre."\n".$evenement->getSalle()->getNumSalle();
        $titre = $titre."\nMr ".$evenement->getProfesseur()->getUsername();
        $titre = $titre."\n".$evenement->getMatiere()->getNom();
        $evenement->setTitle($titre);

        $em->persist($evenement);
        $em->flush();
        return $this->redirect($this->generateUrl('edt_entite_view', ['entite' => 'Evenement']));
      }
      return $this->render('EDTBundle:Admin/Entite:addEvenement.html.twig', ['form' => $form->createView()]);
    }

/*=================================================================*/

    public function ajouterProfMatiereAction(Request $request){
      $profMat = new ProfMatiere();
      /*$form = $this->get('form.factory')->create( EvenementType::class, $evenement);*/
      $form = $this->createForm(new ProfMatiereType($this->getDoctrine()->getManager()), $profMat);
      //le formulaire généré va hydrater l'objet $profMat
      if ($form->handleRequest($request)->isValid()){
        $em=$this->getDoctrine()->getManager();
        $em->persist($profMat);
        $em->flush();
        return $this->redirect($this->generateUrl('edt_entite_view', ['entite' => 'ProfMatiere']));
      }
      return $this->render('EDTBundle:Admin/Entite:addProfMatiere.html.twig', ['form' => $form->createView()]);
    }


















/* ------------------------------------------------------------------------------------------- */

  public function ajouterGroupeAction(Request $request){
    $groupe = new Groupe();
    $form = $this->get('form.factory')->create( GroupeType::class, $groupe);
    //le formulaire généré va hydrater l'objet $salle
    if ($form->handleRequest($request)->isValid()){
      $em=$this->getDoctrine()->getManager();
      /*dump($groupe);die;*/
      $etudiants = $groupe->getEtudiants();
      foreach($etudiants as $etudiant){
        $etudiant->setGroupe($groupe);
        $em->persist($etudiant);
      }
      $em->persist($groupe);
      $em->flush();
      $request->getSession()->getFlashBag()->add('notice', 'Groupe bien enregistré');
      return $this->redirect($this->generateUrl('edt_entite_view', ['entite' => 'Groupe']));
    }
    /*return $this->render('EDTBundle:Admin:addEntite.html.twig', ['form' => $form->createView()]);*/
    return $this->render('EDTBundle:Admin:addEntite.html.twig', ['form' => $form->createView()]);
  }


  /*-----------------------------------------------------------------------------------------------------*/

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
      /*return $this->render('EDTBundle:Admin:addEntite.html.twig', ['form' => $form->createView()]);*/
      return $this->render('EDTBundle:Admin/Entite:addSalle.html.twig', ['form' => $form->createView()]);
    }

    public function ajouterMatiereAction(Request $request){
      $matiere = new Matiere();
      $form = $this->get('form.factory')->create( MatiereType::class, $matiere);
      //le formulaire généré va hydrater l'objet $salle
      if ($form->handleRequest($request)->isValid()){
        /*
         A voir si il y mieux :
           1)mémoriser les seances dans une autre zone mémoire
           2)suppression des séances attachées à la matiere
             car on ne connait pas encore l'id de la matiere est donc impossible de persister avec une clé
             matiere_id à null pour les séances
          3) persist de la matiere sans les séances
          4) pour chaque séance mémorisée, on lui indique la matiere
          5) persist des seances qui possède mtn l'id de la matiere.
        */
        // suppression de la persistance en cascade dans l'entity matiere pour l'attribut seance.
       //$seances = clone $matiere->getSeances(); // -- Suprrime le 12 /04/16
       // $matiere->getSeances()->clear(); // -- supprime le 12/04/16

      //$seances = $form->getData()->getSeances();// remplacement 1 par cette ligne de code le 12/04/16
      $seances = $matiere->getSeances(); // remplacement 2 ; a voir quelle est la meilleur méthode...
       // --- Fin du cas 1/2 ---
       // On enregistre l'objet $article dans la base de donnÃ©es
       $em = $this->getDoctrine()->getManager();
       $em->persist($matiere);
      // $em->flush();
       foreach ($seances as $seance) {
         $seance->setMatiere($matiere);
         $em->persist($seance);
       }
       $em->flush();
       // --- Fin du cas 2/2 ---
      // - See more at: http://www.tutoriel-symfony2.fr/livre/codesource#sthash.7XJurIje.dpuf

        $request->getSession()->getFlashBag()->add('notice', 'Annonce bien enregistrée.');
        return $this->redirect($this->generateUrl('edt_entite_view', ['entite' => 'Matiere']));
      }
      return $this->render('EDTBundle:Admin:addEntite.html.twig', ['form' => $form->createView()]);
    }

/*
    public function editerEntiteAction($entite, $id){
      return new Reponse ('<body> Page pour édité l\entite : '.$entite.'d\id :'.$id.'</body>');
    }*/
    public function editerSalleAction(Request $request, $id){
      $formFactory = $this->get('form.factory');
      $em = $this->getDoctrine()->getManager();
      $objet = $em->getRepository('EDTBundle:Salle')->find($id);
      $form = $formFactory->create (SalleType::class, $objet);
      if ($objet ==null){
        throw new NotFoundHttpException();
      }
      //le formulaire généré va hydrater l'objet $salle
      if ($form->handleRequest($request)->isValid()){
      /*  $em=$this->getDoctrine()->getManager();*/
        $em->persist($objet);
        $em->flush();
        $request->getSession()->getFlashBag()->add('notice', 'Annonce bien enregistrée.');
        return $this->redirect($this->generateUrl('edt_entite_view', ['entite' => $entite]));
      }
      return $this->render('EDTBundle:Admin:addEntite.html.twig', ['form' => $form->createView()]);    }

    public function editerMatiereAction( Request $request, $id){
      $em = $this->getDoctrine()->getManager();

      $matiere = $em->getRepository('EDTBundle:Matiere')->find($id);
      if ($matiere ==null){
        throw new NotFoundHttpException();
      }
      $form = $this->get('form.factory')->create( MatiereType::class, $matiere);
      //le formulaire généré va hydrater l'objet $salle
      if ($form->handleRequest($request)->isValid()){
      //$seances = $form->getData()->getSeances();// remplacement 1 par cette ligne de code le 12/04/16
      $seances = $matiere->getSeances(); // remplacement 2 ; a voir quelle est la meilleur méthode...
       // --- Fin du cas 1/2 ---
       // On enregistre l'objet $article dans la base de donnÃ©es
      /* $em = $this->getDoctrine()->getManager();*/
       $em->persist($matiere);
      // $em->flush();
       foreach ($seances as $seance) {
         $seance->setMatiere($matiere);
         $em->persist($seance);
       }
       $em->flush();
       // --- Fin du cas 2/2 ---
      // - See more at: http://www.tutoriel-symfony2.fr/livre/codesource#sthash.7XJurIje.dpuf

        $request->getSession()->getFlashBag()->add('notice', 'Annonce bien enregistrée.');
        return $this->redirect($this->generateUrl('edt_entite_view', ['entite' => 'Matiere']));
      }
      return $this->render('EDTBundle:Admin:addEntite.html.twig', ['form' => $form->createView()]);
    }

    public function editerEvenementAction(Request $request, $id){
      $em = $this->getDoctrine()->getManager();

      $evenement = $em->getRepository('EDTBundle:Evenement')->find($id);
      if ($evenement ==null){
        throw new NotFoundHttpException();
      }
      /*$form = $this->get('form.factory')->create( EvenementType::class, $evenement);*/
      $form = $this->createForm(new EvenementType($this->getDoctrine()->getManager()), $evenement);
      //le formulaire généré va hydrater l'objet $evenement
      if ($form->handleRequest($request)->isValid()){
        $titre = "";
        $titre = $titre."\n".$evenement->getSalle()->getNumSalle();
        $titre = $titre."\nMr ".$evenement->getProfesseur()->getUsername();
        $titre = $titre."\n".$evenement->getMatiere()->getNom();
        $evenement->setTitle($titre);
        $em->persist($evenement);
        $em->flush();
        return $this->redirect($this->generateUrl('edt_entite_view', ['entite' => 'Evenement']));
      }
      return $this->render('EDTBundle:Admin/Entite:addEvenement.html.twig', ['form' => $form->createView()]);
    }

    public function editerTypeAction(Request $request, $id){
      $em = $this->getDoctrine()->getManager();
      $type = $em->getRepository('EDTBundle:Type')->find($id);
      if ($type == null){
        throw new NotFoundHttpException();
      }
      $form = $this->createForm(TypeType::class, $type);
      if ($form->handleRequest($request)->isValid()){
        $em->persist($type);
        $em->flush();
        return $this->redirect($this->generateUrl('edt_entite_view', ['entite' => 'Type']));
      }
      return $this->render('EDTBundle:Admin:addEntite.html.twig', ['form' => $form->createView()]);
    }
    public function editerGroupeAction(Request $request, $id){
      $em = $this->getDoctrine()->getManager();
      $groupe = $em->getRepository('EDTBundle:Groupe')->find($id);
      $form = $this->get('form.factory')->create( GroupeType::class, $groupe);
      //le formulaire généré va hydrater l'objet $salle
      if ($form->handleRequest($request)->isValid()){
        $etudiants = $groupe->getEtudiants();
        foreach($etudiants as $etudiant){
          $etudiant->setGroupe($groupe);
          $em->persist($etudiant);
        }
        $em->persist($groupe);
        $em->flush();
        $request->getSession()->getFlashBag()->add('notice', 'Groupe bien enregistré');
        return $this->redirect($this->generateUrl('edt_entite_view', ['entite' => 'Groupe']));
      }
      /*return $this->render('EDTBundle:Admin:addEntite.html.twig', ['form' => $form->createView()]);*/
      return $this->render('EDTBundle:Admin:addEntite.html.twig', ['form' => $form->createView()]);
    }

    /*======================Fin EditerAction===============================*/

    public function deleteEntiteAction($entite, $id){
      $em = $this->getDoctrine()->getManager();
      if ($entite == 'Etudiant' || $entite == 'Professeur'){
        $bundle = 'User';
      }
      else{
        $bundle = 'EDT';
      }
      $entite_a_supprimer = $em->getRepository($bundle.'Bundle:'.$entite)->find($id);
      $em->remove($entite_a_supprimer);
      $em->flush();
      $url = $this->generateUrl('admin_home_page');
      return new Response('<body>Entite bien supprimee.<a href='.$url.'> Page admin</a></body>');
    }


////--------------------USER  ----------------------

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
        $user->setEnabled(true);
        $em->persist($user);
        $em->flush();
        $request->getSession()->getFlashBag()->add('notice', 'Annonce bien enregistrée.');
        return $this->redirect($this->generateUrl('edt_user_view', ['entite' => $entite]));
      }
      return $this->render('EDTBundle:Admin/Entite:add'.$entite.'.html.twig', ['form' => $form->createView()]);
    }


    public function editUserAction(Request $request, $entite, $id){
      $em = $this->getDoctrine()->getManager();
      switch ($entite){
        case 'Etudiant' :
          $user = $em->getRepository('UserBundle:Etudiant')->find($id);
          $form = $this->get('form.factory')->create(EtudiantType::class, $user);
        break;
        case 'Professeur' :
          $user = $em->getRepository('UserBundle:Professeur')->find($id);
          $form = $this->get('form.factory')->create(ProfesseurType::class, $user);
        break;
      }
      if ($user ==null){
        throw new NotFoundHttpException();
      }
      if ($form->handleRequest($request)->isValid()){
        $em=$this->getDoctrine()->getManager();
        $em->persist($user);
        $em->flush();
        $request->getSession()->getFlashBag()->add('notice', 'Annonce bien enregistrée.');
        return $this->redirect($this->generateUrl('edt_user_view', ['entite' => $entite]));
      }
    return $this->render('EDTBundle:Admin/Entite:add'.$entite.'.html.twig', ['form' => $form->createView()]);
    }

    public function afficherMesCoursAction(){

      $user = $this->get('security.token_storage')->getToken()->getUser();
      $prof = $this->getDoctrine()->getManager()
          ->getRepository('UserBundle:Professeur')
          ->getMatieres($user->getId());
    //  dump($prof);die;
      return new Response('<body> Matiere = '.$prof[0]->getProfMatieres()[0]->getMatiere()->getNom().' </body>');
    }





}
