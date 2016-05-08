<?php
// src/EDTBundle/DataFixtures/ORM/LoadMatiere.php

namespace UserBundle\DataFixtures\ORM;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use UserBundle\Entity\User;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

class LoadUserData extends AbstractFixture implements OrderedFixtureInterface, ContainerAwareInterface
{
    /**
     * @var ContainerInterface
     */
    private $container;

    /**
     * {@inheritDoc}
     */
    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
    }
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
