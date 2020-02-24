<?php


namespace EcommerceBundle\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class RatingVeloController extends Controller
{
    public function newRating(Request $request){
            $em = $this->getDoctrine()->getManager();
            $velo = $em->getRepository('AppBundle:Velo')->find($request->get('id_velo'));
            $rating = $em->getRepository('AppBundle:RatingVelo')->findOneBy(['idUser' =>$this->getUser()->getId(), 'idVelo'=>$velo->getId()]);
            //if current user doesnt have an old rating for this 'velo' create new rating else it will be updated ok fhmtk :^ tbarkallah alik :p hh al5er
            if(!$rating){
                $rating = new \AppBundle\Entity\RatingVelo();
            }
            $rating->setIdUser($this->getUser());
            $rating->setIdVelo($velo);
            $rating->setRating($request->get('rating'));
            $em->persist($rating);
            $em->flush();

            $ratingValue = $em->getRepository('AppBundle:RatingVelo')->calculateRating($velo->getId());
            //here we should calculate the new rating and return it as json response so we could update the view
            return new JsonResponse([
                'rating' => round($ratingValue)
            ]);
        }
}