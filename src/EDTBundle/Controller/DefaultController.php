<?php

namespace EDTBundle\Controller;

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
}
