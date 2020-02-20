<?php


namespace UserBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class VeloController extends Controller
{
    public function LocationAction()

    {

        $em = $this->getDoctrine()->getEntityManager();



        $velo= $em->getRepository('AppBundle:Velo')->findBy([
            'etatVendu'=>0,
            'etatLocation'=>1,

        ]);


        return $this->render('@User/Default/Location.html.twig',array('velo'=>$velo));
    }
    public function RoadBikeAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $repository = $em->getRepository('AppBundle:Velo');
        $query = $em->createQueryBuilder()
            ->select('v')->from('AppBundle:Velo','v')
            ->where('v.categories = :categories')

            ->setParameter('categories','5')
            ->getQuery();

        $velos = $query->getResult();


        return $this->render('@User/Default/location.html.twig', array('velo' => $velos));
    }

    public function KidsBikesAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $repository = $em->getRepository('AppBundle:Velo');
        $query = $em->createQueryBuilder()
            ->select('v')->from('AppBundle:Velo','v')
            ->where('v.categories = :categories')

            ->setParameter('categories','2')
            ->getQuery();

        $velos = $query->getResult();



        return $this->render('@User/Default/location.html.twig', array('velo' => $velos));
    }
    public function MountainBikesAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $repository = $em->getRepository('AppBundle:Velo');
        $query = $em->createQueryBuilder()
            ->select('v')->from('AppBundle:Velo','v')
            ->where('v.categories = :categories')

            ->setParameter('categories','1')
            ->getQuery();

        $velos = $query->getResult();



        return $this->render('@User/Default/location.html.twig', array('velo' => $velos));
    }

    public function SportsBikeAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $repository = $em->getRepository('AppBundle:Velo');
        $query = $em->createQueryBuilder()
            ->select('v')->from('AppBundle:Velo','v')
            ->where('v.categories = :categories')

            ->setParameter('categories','3')
            ->getQuery();

        $velos = $query->getResult();



        return $this->render('@User/Default/location.html.twig', array('velo' => $velos));
    }

    public function CyclocrossBikeAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $repository = $em->getRepository('AppBundle:Velo');
        $query = $em->createQueryBuilder()
            ->select('v')->from('AppBundle:Velo','v')
            ->where('v.categories = :categories')

            ->setParameter('categories','4')
            ->getQuery();

        $velos = $query->getResult();



        return $this->render('@User/Default/location.html.twig', array('velo' => $velos));
    }

    public function prixascAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $repository = $em->getRepository('AppBundle:Velo');
        $query = $em->createQueryBuilder()
            ->select('v')->from('AppBundle:Velo', 'v')
            ->orderBy('v.prix_location', 'ASC')
            ->getQuery();

        $velos = $query->getResult();
        return $this->render('@User/Default/Location.html.twig', array('velo' => $velos));
    }


    public function prixdescendantAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $repository = $em->getRepository('AppBundle:Velo');
        $query = $em->createQueryBuilder()
            ->select('v')->from('AppBundle:Velo', 'v')
            ->orderBy('v.prix_location', 'DESC')
            ->getQuery();

        $velos = $query->getResult();
        return $this->render('@User/Default/Location.html.twig', array('velo' => $velos));
    }




}