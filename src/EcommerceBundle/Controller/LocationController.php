<?php


namespace EcommerceBundle\Controller;


use AppBundle\Form\RatingEventType;
use blackknight467\StarRatingBundle\Form\RatingType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

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

    public function LocationAction(Request $request)

    {

        $em = $this->getDoctrine()->getEntityManager();


        $velo = $em->getRepository('AppBundle:Velo')->findBy([
            'etatVendu' => 0,
            'etatLocation' => 1,


        ]);







            return $this->render('@Ecommerce/Default/Location.html.twig', array
            ('velo' => $velo ));

    }

    public function AfficherveloAction($id)
    {
        $em = $this->getDoctrine()->getEntityManager();
        $velo = $em->getRepository('AppBundle:Velo')->findById($id);
        return $this->render('@Ecommerce/Panier/Affichervelo.html.twig', array('velo' => $velo));
    }

    public function ratingAction(Request $request, $id,$valeur)
    {
        $em = $this->getDoctrine()->getManager();
        $velo = $em->getRepository("AppBundle:Velo")->find($id);
        $r = $velo->getRating();
        $form = $this->createForm(RatingEventType::class, $velo);
        $form->handleRequest($request);
        if ($form->isSubmitted()) {
            $velo->setNbruser($velo->getNbruser() + 1);
            $velo->setRating($velo->getRating() + $r);
            $a = ($form['rating']->getData() + $r) / $velo->getNbruser();
            $velo->setRating($a);
            $em->persist($velo);
            $em->flush();
            return new Response("Done");
        } else
            return new Response("error");
        }

    }

