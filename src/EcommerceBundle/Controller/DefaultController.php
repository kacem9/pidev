<?php

namespace EcommerceBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction()
    {
        $em = $this->getDoctrine()->getEntityManager();
        $velo = $em->getRepository('AppBundle:Velo')->findAll();
        return $this->render('@Ecommerce/index.html.twig',array('velo'=>$velo));
    }
}
