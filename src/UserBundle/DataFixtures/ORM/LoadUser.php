<?php
// src/EDTBundle/DataFixtures/ORM/LoadMatiere.php

namespace UserBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use UserBundle\Entity\User;
class LoadUserData implements FixtureInterface, ContainerAwareInterface
{
    //.. $container declaration & setter

    public function load(ObjectManager $manager)
    {
        $userManager = $this->container->get('fos_user.user_manager');
        $user = $userManager->createUser();
        $user->setUsername('admin');
        $user->setEmail('admin@admin.fr');
        $user->setPlainPassword('passadmin');
        $user->setEnabled(true);
        $user->setRoles(array('ROLE_ADMIN'));
        // Update the user
        $userManager->updateUser($user, true);
    }

  function getOrder(){
    return 1;
  }
}
