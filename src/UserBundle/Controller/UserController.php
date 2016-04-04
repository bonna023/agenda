<?php

namespace UserBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use FOS\UserBundle\Entity\User;

class UserController extends Controller
{
    public function indexAction()
    {
        return $this->render('UserBundle:Default:index.html.twig');
    }
    public function setRoleAction($role){ 
        $userManager = $this->get('fos_user.user_manager');
        $security = $this->get('security.token_storage');
        $token = $security->getToken();
        if ($token === NULL){
            //si pas derrière un parefeu.
        }
       $user = $token->getUser();
       $user->addRole('ROLE_'.$role);
       $userManager->updateUser($user);
       return new Response('<body> Role '.$role.' ajoute à l\'utilisateur courant.</body>');
    }

}
