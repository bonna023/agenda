<?
// src/EDTBundle/DataFixtures/ORM/LoadUsers.php

namespace src\EDTBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\FixturesInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\AbstractFixture;

use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

use UserBundle\Entity\Etudiant;

class LoadEtudiant extends AbstractFixtures implements OrderedFixtureInterface, ContainerAwareInterface
{
  /**
   * @var ContainerInterface
   */
   private $container;

   /**
    * {@inheritDoc}
    */
    public function setContainer(ContainerInterface $container = null){
      $this->$container = $container;
    }

   /**
    * Get the order of this fixture
    *
    * @return integer
    */
    function getOrder()
    {
       return 5;
    }

  public function load(ObjectManager $manager){
    $prenoms = array('Dimitri, Jérémy', 'Mehdi', 'Thomas','Cyril','Christophe' );
    $noms = array('Albora' , 'Bonnardel', 'Hadid', 'Lenoir','Rabat', 'Jaillet');
    $role = array('ROLE_USER', 'ROLE_USER', 'ROLE_USER', 'ROLE_USER', 'ROLE_PROF', 'ROLE_PROF');
    $numEtudiants=array('001','002','003','004');

    $manager = $this->container->get('fos_user.user_manager');
    for ($i=0 ; $i<lenth($prenoms);$i++){
      if ($role[$i] ==='ROLE_USER'){
        $user = $manager->createUser();//new Etudiant(); //$manger->createUser();
        $user->setEmail($prenoms[$i].'.'.$noms[$i].'@'.'etudiant.univ-reims.fr');
      //  $user->setNumEtudiant($numEtudiants[$i]);
      }
      else{
        $user = $manager->createUser()//new Professeur();
        $user->setEmail($prenoms[$i].'.'.$noms[$i].'@'.'univ-reims.fr');
      }
      $user->setUsername($prenoms[$i]);
      $user->setEnabled(true);
      $user->setPassword($nom);
      $manager->updateUser($user);
    //  $manager->persist($user);
      //$this->addSeance
    }
  //  $manager->flush();
  }

}
