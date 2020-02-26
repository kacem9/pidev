<?php


namespace EcommerceBundle\Controller;
use AppBundle\Entity\Commande;
use AppBundle\Entity\CommentaireProd;
use DateTime;
use AppBundle\Entity\LigneDeCommande;
use AppBundle\Entity\Velo;
use AppBundle\Form\LigneDeCommandeType;
use Symfony\Component\HttpFoundation\Request;
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




                $em->persist($j);
                $em->flush();

            }

            return $this->render('@Ecommerce/Achat/show.html.twig', array(
                'produit' => $j,
                'usrid' => $userId,
                'img' => $userimg,

            ));
        }


    public function addPanierAction(Request $request)
    {

        $session = $request->getSession();

        if (!$session->has('panier')) $session->set('panier', array());


        $em = $this->getDoctrine()->getManager();
        $produits = $em->getRepository('AppBundle:Velo')->findArray(array_keys($session->get('panier')));

        return $this->render('@Ecommerce/Achat/cart.html.twig', array('produits' => $produits,
            'panier' => $session->get('panier')));

    }
    public function ajouterAction(Request $request,$id)
    {

        $session = $request->getSession();
        if (!$session->has('panier')) $session->set('panier',array());
        $panier = $session->get('panier');
        if (array_key_exists($id, $panier)) {
            if ($request->query->get('qte') != null)
                $panier[$id] = $request->query->get('qte');
            $this->get('session')->getFlashBag()->add('success','Quantité modifié avec succès');
        } else
            if ($request->query->get('qte') != null){
                $panier[$id] = $request->query->get('qte');
            }
            else if (!array_key_exists($id, $panier)){
                $panier[$id] = 1;
                $this->get('session')->getFlashBag()->add('success','Article ajouté avec succès');
            }

        $session->set('panier',$panier);



        return $this->redirect($this->generateUrl('addPanier'));

    }

    public function supprimerCardAction(Request $request,$id)
    {
        $session = $request->getSession();
        $panier = $session->get('panier');

        if (array_key_exists($id, $panier))
        {
            unset($panier[$id]);
            $session->set('panier',$panier);
            $this->get('session')->getFlashBag()->add('success','Article supprimé avec succès');
        }

        return $this->redirect($this->generateUrl('addPanier'));
    }
    public function commander_veloAction(Request $request,$id)
    {
        $session = $request->getSession();

        if ($session->has('panier'))
            $panier = $session->get('panier');
        $lcommande = new LigneDeCommande();
        $em = $this->getDoctrine()->getManager();

        $produit = $em->getRepository('AppBundle:Velo')->findArray(array_keys($session->get('panier')
        /*'id' => $panier*/));

        $lcommande = new LigneDeCommande();
        $form = $this->createForm(LigneDeCommandeType::class, $lcommande);
        $form->handleRequest($request);
        if ($form->isValid()) {
            $this->get('session')->set('idVelo', $produit);

            $this->get('session')->set('prixtotal', $id);

            $this->get('session')->set('addresse', $lcommande->getAdresse());
            $this->get('session')->set('addresse2', $lcommande->getAdresse2());
            $this->get('session')->set('ville', $lcommande->getVille());
            $this->get('session')->set('codepostal', $lcommande->getCodePostal());
            $this->get('session')->set('numtel', $lcommande->getNumTel());


            return $this->redirectToRoute('ajout_ligne_commandevelo');

        }


        return $this->render("@Ecommerce/Achat/AjoutLigneDeCommande.html.twig",
            array('produit' => $produit, 'lcommande' => $lcommande, 'form' => $form->createView()));
    }
    public function ajout_ligne_commandeveloAction(Request $request)
    {
        $session = $request->getSession();

        $user=$this->getUser();

        $dateCommande = new \DateTime();
        $dateLivraison = new \DateTime('+2 day');
$etat=0;
$vendu=1;

        $datmax = new DateTime('+ 5 minute');

        $commande = new Commande();

        $this->get('session')->set('datmax', $datmax);

        $em = $this->getDoctrine()->getManager();

        $commande->setDateCommande($dateCommande);
        $commande->setDateMax($datmax);
        $commande->setIdUser($user);
        $commande->setEtatLocation($etat);
        $commande->setEtatVendu($vendu);
        $em->persist($commande);
        $em->flush();
        $panier = $this->get('session')->get('panier');

        $commande = $em->getRepository( "AppBundle:Commande")->findOneBy(array('idUser' => $user->getId()),


            array('id' => 'DESC')




        );
        $prod = $em->getRepository("AppBundle:Velo")->findArray(array_keys($session->get('panier')));
        $i = 0;

        ksort($panier);

        foreach ($prod as $p) {
            $lcommande = new  LigneDeCommande();
            $lcommande->setDateLivraison($dateLivraison);
            $lcommande->setIdVelo($p);
            $lcommande->setPrixTotal(array_values($panier)[$i] * $p->getPrice());
            $lcommande->setQuantite(array_values($panier)[$i]);
            $p->setQuantity($p->getQuantity() - $lcommande->getQuantite());
            $lcommande->setAdresse($this->get('session')->get('addresse'));
            $lcommande->setAdresse2($this->get('session')->get('addresse2'));
            $lcommande->setVille($this->get('session')->get('ville'));
            $lcommande->setCodePostal($this->get('session')->get('codepostal'));
            $lcommande->setNumTel($this->get('session')->get('numtel'));
            $lcommande->setEtatVendu('1');
            $lcommande->setEtatLocation('0');
            $lcommande->setIdCommande($commande);

            $em->persist($lcommande);
            $em->flush();
            $i++;
            $em = $this->getDoctrine()->getManager();

        }


        unset($panier);
        return $this->redirectToRoute('afficher_commandeVelo');

        return $this->render("@Ecommerce/Achat/afficheLigneDeCommande.html.twig",
            array('lcommande' => $lcommande));
    }
    public function afficherCommandeVeloAction()
    {
        $user = $this->getUser();
        $em = $this->getDoctrine()->getManager();



        $em = $this->getDoctrine()->getManager();
        $commande = $em->getRepository("AppBundle:Commande")->findby(array('idUser' => $user->getId(),
            'etat_vendu'=>'1',
            'etatLocation'=>'0',

        ));

        return $this->render('@Ecommerce/Achat/afficherCommande.html.twig', array(
            'commande' => $commande));
    }
    public function ligne_commande_affVeloAction($id)
    {
        $user = $this->getUser();

        $em = $this->getDoctrine()->getManager();

        $lcommande = $em->getRepository("AppBundle:LigneDeCommande")->findby(array('idCommande' => $id,
            'etat_vendu'=>'1',
            'etatLocation'=>'0',

        ));


        $datesys = new DateTime();


        return $this->render('@Ecommerce/Achat/afficheLigneDeCommande.html.twig', array(
            'lst' => $lcommande, 'datesys' => $datesys));
    }

    public function bikepartsAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $velos = $em->getRepository('AppBundle:Produit')->findAll();




        return $this->render('@Ecommerce/Achat/bikeparts.html.twig', array('velo' => $velos));
    }

public function showBikeAction($id,Request $request)
{
$user = $this->container->get('security.token_storage')->getToken()->getUser();
$em = $this->getDoctrine()->getManager();
    //   echo $tit;


if (TRUE === $this->get('security.authorization_checker')->isGranted('ROLE_ACHETEUR')) {
    // user is logged in


$userId = $user->getId();

$userimg = $user->getPhoto();
$j = $em->getRepository('AppBundle:Produit')->find($id);

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
$cm->setDatePublication(new \DateTime());
    $cmt = $em->getRepository(CommentaireProd::class)->clc($id);
    $cm->setRate(($cm->getRate() + ($cmt[0][1] + 0)) / 5);

$em = $this->getDoctrine()->getManager();

$em->persist($cm);


$em->flush();

$em->persist($j);
$em->flush();
$C = $em->getRepository('AppBundle:CommentaireProd')->fiii($id);

}

return $this->render('@Ecommerce/Achat/showProduit.html.twig', array(
    'produit' => $j,
    'com' => $C,
    'usrid' => $userId,
    'img' => $userimg,

));
} else {

    return $this->redirectToRoute('fos_user_security_login');
}
}
    public function remComAction($id,$prodid)
    {   $em=$this->getDoctrine()->getManager();
        //echo $id;

        $B=$em->getRepository('AppBundle:CommentaireProd')->find($id) ;
        if (!$B) {
            throw $this->createNotFoundException('No Comment found for id '.$id);
        }
        $em->remove($B);
        $em ->flush();

        return $this->redirectToRoute('showBike',array('id'=>$prodid));

    }

    public function ligne_commande_modifierVeloAction(Request $request, $id)
    {
        $user = $this->getUser();


        $em = $this->getDoctrine()->getManager();

        $lcommande = $em->getRepository("AppBundle:LigneDeCommande", $id)->find($id);

        $form = $this->createForm(LigneDeCommandeType::class, $lcommande);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em->persist($lcommande);
            $em->flush();
            return $this->redirectToRoute('afficher_commandeVelo');
        }
        return $this->render("@Ecommerce/Achat/modifierLigneDeCommandeVelo.html.twig",
            array('form' => $form->createView()));
    }

    public function ligne_commande_supprimerVeloAction($id)
    {
        $user = $this->getUser();


        $datesys = new DateTime();
        $em = $this->getDoctrine()->getManager();
        $lcommande = $em->getRepository("AppBundle:LigneDeCommande", $id)->find($id);
        $idC = $lcommande->getIdCommande();
        $dm = $this->get('session')->get('datmax');



        $em->remove($lcommande);
        $em->flush();


        $em->detach($lcommande);
        $lcommandee = $em->getRepository("AppBundle:LigneDeCommande", $id)->findBy(array('idCommande' => $idC));

        $commande = $em->getRepository("AppBundle:Commande")->find($idC);

        if (empty($lcommandee)) {
            $em->remove($commande);
            $em->flush();
        }
        return $this->redirectToRoute('afficher_commandeVelo');

        return $this->render("@Ecommerce/Achat/afficheLigneDeCommande.html.twig.html.twig", array('dm' => $dm, 'datesys' => $datesys));
    }



}