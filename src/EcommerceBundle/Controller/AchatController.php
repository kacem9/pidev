<?php


namespace EcommerceBundle\Controller;
use AppBundle\Entity\Velo;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class AchatController extends Controller
{


    public function RoadBikeAchatAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $repository = $em->getRepository('AppBundle:Velo');
        $query = $em->createQueryBuilder()
            ->select('v')->from('AppBundle:Velo', 'v')
            ->where('v.categories = :categories')
            ->andwhere('v.etatVendu = 1')
            ->andwhere('v.etatLocation = 0')
            ->setParameter('categories', '5')
            ->getQuery();

        $velos = $query->getResult();


        return $this->render('@Ecommerce/Achat/RoadBike.html.twig', array('velo' => $velos));
    }

    public function KidsBikesAchatAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $repository = $em->getRepository('AppBundle:Velo');
        $query = $em->createQueryBuilder()
            ->select('v')->from('AppBundle:Velo', 'v')
            ->where('v.categories = :categories')
            ->andwhere('v.etatVendu = 1')
            ->andwhere('v.etatLocation = 0')
            ->setParameter('categories', '2')
            ->getQuery();

        $velos = $query->getResult();


        return $this->render('@Ecommerce/Achat/KidsBike.html.twig', array('velo' => $velos));
    }

    public function MountainBikesAchatAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $repository = $em->getRepository('AppBundle:Velo');
        $query = $em->createQueryBuilder()
            ->select('v')->from('AppBundle:Velo', 'v')
            ->where('v.categories = :categories')
            ->andwhere('v.etatVendu = 1')
            ->andwhere('v.etatLocation = 0')
            ->setParameter('categories', '1')
            ->getQuery();

        $velos = $query->getResult();


        return $this->render('@Ecommerce/Achat/MountainBike.html.twig', array('velo' => $velos));
    }

    public function SportsBikeAchatAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $repository = $em->getRepository('AppBundle:Velo');
        $query = $em->createQueryBuilder()
            ->select('v')->from('AppBundle:Velo', 'v')
            ->where('v.categories = :categories')
            ->andwhere('v.etatVendu = 1')
            ->andwhere('v.etatLocation = 0')
            ->setParameter('categories', '3')
            ->getQuery();

        $velos = $query->getResult();


        return $this->render('@Ecommerce/Achat/SportsBike.html.twig', array('velo' => $velos));
    }

    public function CyclocrossBikeAchatAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $repository = $em->getRepository('AppBundle:Velo');
        $query = $em->createQueryBuilder()
            ->select('v')->from('AppBundle:Velo', 'v')
            ->where('v.categories = :categories')
            ->andwhere('v.etatVendu = 1')
            ->andwhere('v.etatLocation = 0')
            ->setParameter('categories', '4')
            ->getQuery();

        $velos = $query->getResult();


        return $this->render('@Ecommerce/Achat/Cylo.html.twig', array('velo' => $velos));
    }

    public function showAchatAction(Request $request, $id)
    {
        $user = $this->container->get('security.token_storage')->getToken()->getUser();
        $em = $this->getDoctrine()->getManager();
        //   echo $tit;


        if (TRUE === $this->get('security.authorization_checker')->isGranted('ROLE_ACHETEUR')) {
            // user is logged in


            $userId = $user->getId();

            $userimg = $user->getPhoto();
            $j = $em->getRepository('AppBundle:Velo')->find($id);

            $C = $em->getRepository('AppBundle:CommentaireProd')->fiii($id);


            if ($request->isMethod('POST')) {
                $comm = $request->get('comment');
                $rate = $request->get('rating');

                $date = new \DateTime();

                $cm = new CommentaireProd();
                $cm->setComment($comm);
                $cm->setRate($rate);
                $cm->setIdUser($user);
                $cm->setIdProd($j);
                $cm->setDate($date);
                $em = $this->getDoctrine()->getManager();


                $em->persist($cm);


                $em->flush();
                $cmt = $em->getRepository(CommentaireProd::class)->clc($id);

                $j->setRate(($j->getRate() + ($cmt[0][1] + 0)) / 5);
                $em->persist($j);
                $em->flush();
                $C = $em->getRepository('ProduitBundle:CommentaireProd')->fiii($id);

            }

            return $this->render('@Ecommerce/Achat/show.html.twig', array(
                'produit' => $j,
                'com' => $C,
                'usrid' => $userId,
                'img' => $userimg,

            ));
        } else {

            return $this->redirectToRoute('fos_user_security_login');
        }
    }

    public function addPanierAction(SessionInterface $session, $id)
    {


        $em = $this->getDoctrine()->getManager();
        $pt = $em->getRepository('AppBundle:Velo')->find($id);
        $pt->setQuantity($pt->getQuantity() - 1);
        $em->persist($pt);
        $em->flush();

        $panier = $session->get('panier', []);

        if (!empty($panier[$id])) {
            $panier[$id]++;
        } else {
            $panier[$id] = 1;
        }
        $session->set('panier', $panier);

        return $this->redirectToRoute('PanierAchaat');
    }

    public function PanierAchaatAction(SessionInterface $session)
    {



    }
}