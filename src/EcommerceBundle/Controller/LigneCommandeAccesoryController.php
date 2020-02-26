<?php


namespace EcommerceBundle\Controller;

use DateTime;
use AppBundle\Entity\Commande;
use AppBundle\Entity\LigneDeCommande;
use AppBundle\Form\LigneDeCommandeType;
use AppBundle\Entity\Produit;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class LigneCommandeAccesoryController extends Controller
{

public function addPanierAccesoryAction(Request $request)
    {

        $session = $request->getSession();

        if (!$session->has('panier')) $session->set('panier', array());


        $em = $this->getDoctrine()->getManager();
        $produits = $em->getRepository('AppBundle:Produit')->findArray(array_keys($session->get('panier')));

        return $this->render('@Ecommerce/Achat/cartAccesory.html.twig', array('produits' => $produits,
            'panier' => $session->get('panier')));

    }

    public function ajouterAccesoryAction(Request $request,$id)
    {

        $session = $request->getSession();
        if (!$session->has('panier')) $session->set('panier', array());
        $panier = $session->get('panier');
        if (array_key_exists($id, $panier)) {
            if ($request->query->get('qte') != null)
                $panier[$id] = $request->query->get('qte');
            $this->get('session')->getFlashBag()->add('success', 'Quantité modifié avec succès');
        } else
            if ($request->query->get('qte') != null) {
                $panier[$id] = $request->query->get('qte');
            } else if (!array_key_exists($id, $panier)) {
                $panier[$id] = 1;
                $this->get('session')->getFlashBag()->add('success', 'Article ajouté avec succès');
            }

        $session->set('panier', $panier);


        return $this->redirect($this->generateUrl('addPanierAccesory'));

    }

    public function supprimerCardAccesoryAction(Request $request, $id)
    {
        $session = $request->getSession();
        $panier = $session->get('panier');

        if (array_key_exists($id, $panier)) {
            unset($panier[$id]);
            $session->set('panier', $panier);
            $this->get('session')->getFlashBag()->add('success', 'Article supprimé avec succès');
        }

        return $this->redirect($this->generateUrl('addPanierAccesory'));
    }

    public function commander_AccesoryAction(Request $request, $id)
    {
        $session = $request->getSession();

        if ($session->has('panier'))
            $panier = $session->get('panier');
        $lcommande = new LigneDeCommande();
        $em = $this->getDoctrine()->getManager();

        $produit = $em->getRepository('AppBundle:Produit')->findArray(array_keys($session->get('panier')
        /*'id' => $panier*/));

        $lcommande = new LigneDeCommande();
        $form = $this->createForm(LigneDeCommandeType::class, $lcommande);
        $form->handleRequest($request);
        if ($form->isValid()) {
            $this->get('session')->set('idProd', $produit);

            $this->get('session')->set('prixtotal', $id);

            $this->get('session')->set('addresse', $lcommande->getAdresse());
            $this->get('session')->set('addresse2', $lcommande->getAdresse2());
            $this->get('session')->set('ville', $lcommande->getVille());
            $this->get('session')->set('codepostal', $lcommande->getCodePostal());
            $this->get('session')->set('numtel', $lcommande->getNumTel());


            return $this->redirectToRoute('ajout_ligne_commandeAccesory');

        }


        return $this->render("@Ecommerce/Achat/AjoutLigneDeCommandeAccesory.html.twig",
            array('produit' => $produit, 'lcommande' => $lcommande, 'form' => $form->createView()));
    }

    public function ajout_ligne_commandeAccesoryAction(Request $request)
    {
        $session = $request->getSession();

        $user = $this->getUser();

        $dateCommande = new \DateTime();
        $dateLivraison = new \DateTime('+2 day');
        $etat = 0;
        $vendu = 2;

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

        $commande = $em->getRepository("AppBundle:Commande")->findOneBy(array('idUser' => $user->getId()),


            array('id' => 'DESC')


        );
        $prod = $em->getRepository("AppBundle:Produit")->findArray(array_keys($session->get('panier')));
        $i = 0;

        ksort($panier);

        foreach ($prod as $p) {
            $lcommande = new  LigneDeCommande();
            $lcommande->setDateLivraison($dateLivraison);
            $lcommande->setIdProd($p);


            $lcommande->setPrixTotal(array_values($panier)[$i] * $p->getPrice());
            $lcommande->setQuantite(array_values($panier)[$i]);
            $p->setQuantity($p->getQuantity() - $lcommande->getQuantite());
            $lcommande->setAdresse($this->get('session')->get('addresse'));
            $lcommande->setAdresse2($this->get('session')->get('addresse2'));
            $lcommande->setVille($this->get('session')->get('ville'));
            $lcommande->setCodePostal($this->get('session')->get('codepostal'));
            $lcommande->setNumTel($this->get('session')->get('numtel'));
            $lcommande->setEtatVendu('2');
            $lcommande->setEtatLocation('0');
            $lcommande->setIdCommande($commande);

            $em->persist($lcommande);
            $em->flush();
            $i++;
            $em = $this->getDoctrine()->getManager();

        }


        unset($panier);
        return $this->redirectToRoute('afficherCommandeAccesory');

        return $this->render("@Ecommerce/Achat/afficheLigneDeCommandeAccesory.html.twig",
            array('lcommande' => $lcommande));
    }

    public function afficherCommandeAccesoryAction()
    {
        $user = $this->getUser();
        $em = $this->getDoctrine()->getManager();


        $em = $this->getDoctrine()->getManager();
        $commande = $em->getRepository("AppBundle:Commande")->findby(array('idUser' => $user->getId(),
            'etat_vendu' => '2',
            'etatLocation' => '0',

        ));

        return $this->render('@Ecommerce/Achat/afficherCommandeAccessory.html.twig', array(
            'commande' => $commande));
    }

    public function afficheLigneDeCommandeAccesoryAction($id)
    {
        $user = $this->getUser();

        $em = $this->getDoctrine()->getManager();

        $lcommande = $em->getRepository("AppBundle:LigneDeCommande")->findby(array('idCommande' => $id,
            'etat_vendu' => '2',
            'etatLocation' => '0',

        ));


        $datesys = new DateTime();


        return $this->render('@Ecommerce/Achat/afficheLigneDeCommandeAccesory.html.twig', array(
            'lst' => $lcommande, 'datesys' => $datesys));
    }
    public function ModifierLigneDeCommandeAccesoryAction(Request $request, $id)
    {
        $user = $this->getUser();


        $em = $this->getDoctrine()->getManager();

        $lcommande = $em->getRepository("AppBundle:LigneDeCommande", $id)->find($id);

        $form = $this->createForm(LigneDeCommandeType::class, $lcommande);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em->persist($lcommande);
            $em->flush();
            return $this->redirectToRoute('afficherCommandeAccesory');
        }
        return $this->render("@Ecommerce/Achat/modifierLigneDeCommandeAccesory.html.twig",
            array('form' => $form->createView()));
    }

    public function SupprimerLigneDeCommandeAccesoryAction(Request $request, $id)
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
        return $this->redirectToRoute('afficherCommandeAccesory');

        return $this->render("@Ecommerce/Achat/afficheLigneDeCommandeAccesory.html.twig", array('dm' => $dm, 'datesys' => $datesys));
    }

}
