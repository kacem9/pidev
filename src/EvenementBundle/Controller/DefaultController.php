<?php

namespace EvenementBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function actualiteAction()
    {


        $em = $this->getDoctrine()->getEntityManager();
        $actualites = $em->getRepository('AppBundle:Actualite')->findAll();

        return $this->render('@Evenement/index.html.twig',array('actualites'=>$actualites));
    }
}
