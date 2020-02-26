<?php

namespace EcommerceBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
class DefaultController extends Controller
{
    public function indexAction()
    {
        $em = $this->getDoctrine()->getEntityManager();
        $velo= $em->getRepository('AppBundle:Velo')->findBy([
                'etatVendu'=>1,
                'etatLocation'=>0,

            ]

        );        $velos = $em->getRepository('AppBundle:Velo')->AfficherVelo();
        $produits= $em->getRepository('AppBundle:Produit')->findAll();
        $event= $em->getRepository('AppBundle:Event')->findBy([
                'etat'=>1,


            ]

        );


        return $this->render('@Ecommerce/index.html.twig',array('velo'=>$velo,'produits'=>$produits,
            'velos'=>$velos,'event'=>$event));
    }
}
