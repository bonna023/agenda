<?php
// src/EDTBundle/DataFixtures/ORM/LoadMatiere.php

namespace EDTBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use EDTBundle\Entity\Type;

class LoadType implements FixtureInterface
{
  public function load(ObjectManager $manager)
  {
    // Les noms d'utilisateurs à créer
    $listtype = array('CM', 'TP', 'TD','TDI');

    foreach ($listtype as $nomtype) {
      // On crée l'utilisateur
      $type = new Type();

      // Le nom d'utilisateur et le mot de passe sont identiques
      $type->setType($nomtype);

      $manager->persist($type);
    }

    // On déclenche l'enregistrement
    $manager->flush();
  }

  function getOrder(){
    return 2;
  }
}
