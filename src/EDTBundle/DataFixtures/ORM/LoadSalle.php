<?php
// src/EDTBundle/DataFixtures/ORM/LoadSalle.php

namespace EDTBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use EDTBundle\Entity\Salle;
use EDTBundle\Entity\Type;

class LoadSalle implements FixtureInterface
{
  public function load(ObjectManager $manager)
  {
    // Les noms d'utilisateurs à créer
    $listNumSalle = array('1722', '1723', '1724');

    foreach ($listNumSalle as $num) {
      // On crée l'utilisateur
      $salle = new Salle();
      // Le nom d'utilisateur et le mot de passe sont identiques
      $salle->setNumSalle($num);
      $salle->setNumBatiment('17');
      $salle->setCapacite(50);
      $typeCM = $manager->getRepository('EDTBundle:Type')
                        ->findOneBy(['nom' => 'CM']);
dump($typeCM);die;
      $salle->setType($typeCM);

      $manager->persist($salle);
    }

    // On déclenche l'enregistrement
    $manager->flush();
  }

  function getOrder(){
    return 3;
  }
}
