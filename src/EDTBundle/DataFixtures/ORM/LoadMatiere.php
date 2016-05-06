<?php
// src/EDTBundle/DataFixtures/ORM/LoadMatiere.php

namespace UserBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use EDTBundle\Entity\Matiere;

class LoadMatiere implements FixtureInterface
{
  public function load(ObjectManager $manager)
  {
    // Les noms d'utilisateurs à créer
    $listNom = array('Info0601', 'Info0602', 'MA0407','Ppro0501');

    foreach ($listNom as $nom) {
      // On crée l'utilisateur
      $matiere = new Matiere();

      // Le nom d'utilisateur et le mot de passe sont identiques
      $matiere->setNom($nom);

      $manager->persist($matiere);
    }

    // On déclenche l'enregistrement
    $manager->flush();
  }

  function getOrder(){
    return 1;
  }
}
