<?php


namespace EcommerceBundle\Controller;


use AppBundle\Form\RatingEventType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class LocationController extends Controller
{


    public function RoadBikeAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $repository = $em->getRepository('AppBundle:Velo');
        $query = $em->createQueryBuilder()
            ->select('v')->from('AppBundle:Velo', 'v')
            ->where('v.categories = :categories')
            ->setParameter('categories', '5')
            ->getQuery();

        $velos = $query->getResult();


        return $this->render('@Ecommerce/Default/location.html.twig', array('velo' => $velos));
    }

    public function KidsBikesAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $repository = $em->getRepository('AppBundle:Velo');
        $query = $em->createQueryBuilder()
            ->select('v')->from('AppBundle:Velo', 'v')
            ->where('v.categories = :categories')
            ->setParameter('categories', '2')
            ->getQuery();

        $velos = $query->getResult();


        return $this->render('@Ecommerce/Default/location.html.twig', array('velo' => $velos));
    }

    public function MountainBikesAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $repository = $em->getRepository('AppBundle:Velo');
        $query = $em->createQueryBuilder()
            ->select('v')->from('AppBundle:Velo', 'v')
            ->where('v.categories = :categories')
            ->setParameter('categories', '1')
            ->getQuery();

        $velos = $query->getResult();


        return $this->render('@Ecommerce/Default/location.html.twig', array('velo' => $velos));
    }

    public function SportsBikeAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $repository = $em->getRepository('AppBundle:Velo');
        $query = $em->createQueryBuilder()
            ->select('v')->from('AppBundle:Velo', 'v')
            ->where('v.categories = :categories')
            ->setParameter('categories', '3')
            ->getQuery();

        $velos = $query->getResult();


        return $this->render('@Ecommerce/Default/location.html.twig', array('velo' => $velos));
    }

    public function CyclocrossBikeAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $repository = $em->getRepository('AppBundle:Velo');
        $query = $em->createQueryBuilder()
            ->select('v')->from('AppBundle:Velo', 'v')
            ->where('v.categories = :categories')
            ->setParameter('categories', '4')
            ->getQuery();

        $velos = $query->getResult();


        return $this->render('@Ecommerce/Default/location.html.twig', array('velo' => $velos));
    }

    public function prixascAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $repository = $em->getRepository('AppBundle:Velo');
        $query = $em->createQueryBuilder()
            ->select('v')->from('AppBundle:Velo', 'v')
            ->orderBy('v.prix', 'ASC')
            ->getQuery();

        $velos = $query->getResult();
        return $this->render('@Ecommerce/Default/Location.html.twig', array('velo' => $velos));
    }


    public function prixdescendantAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $repository = $em->getRepository('AppBundle:Velo');
        $query = $em->createQueryBuilder()
            ->select('v')->from('AppBundle:Velo', 'v')
            ->orderBy('v.prix', 'DESC')
            ->getQuery();

        $velos = $query->getResult();
        return $this->render('@Ecommerce/Default/Location.html.twig', array('velo' => $velos));
    }

    public function LocationAction()

    {

        $em = $this->getDoctrine()->getEntityManager();


        $velo = $em->getRepository('AppBundle:Velo')->findBy([
            'etatVendu' => 0,
            'etatLocation' => 1,

        ]);
        foreach ($velo as $v) {
            $v->ratingValue = round($em->getRepository('AppBundle:RatingVelo')->calculateRating($v->getId()));
        }

        return $this->render('@Ecommerce/Default/Location.html.twig', array('velo' => $velo));
    }

    public function AfficherveloAction($id)
    {
        $em = $this->getDoctrine()->getEntityManager();
        $velo = $em->getRepository('AppBundle:Velo')->findById($id);
        return $this->render('@Ecommerce/Panier/Affichervelo.html.twig', array('velo' => $velo));
    }


    public function BestSelerAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $velos = $em->getRepository('AppBundle:Velo')
            ->createQueryBuilder('v');


        $velos->select('v,COUNT(lc.id) AS mycount')
            ->leftJoin('v.ligneCommande', 'lc')
            ->orderBy('mycount', 'ASC')
            ->getQuery()
            ->getResult();


        return $this->render('@Ecommerce/LigneDeCommande/BestSeller.html.twig', array('velo' => $velos));
    }

    public function newVeloAction()
    {
        $query = $this->getEntityManager()
            ->createQuery("SELECT e FROM AppBundle:Velo v 
            ORDER BY  v.datePublication ASC ")
            ->getQuery();
        $velos = $query->getResult();

        return $this->render('@Ecommerce/Default/Location.html.twig', array('velo' => $velos));
    }

}
