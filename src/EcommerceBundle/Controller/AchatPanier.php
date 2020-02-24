<?php


namespace EcommerceBundle\Controller;


use AppBundle\Entity\Commande;
use AppBundle\Entity\LigneDeCommande;
use AppBundle\Form\LigneDeCommandeType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class AchatPanier extends Controller
{
    public function PutInSessionAction(Request $request,$id)
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
            return $this->redirectToRoute('ajout_ligne_commande');

        }


        return $this->render("@Ecommerce/LigneDeCommande/AjoutLigneDeCommande.html.twig",
            array('produit' => $produit, 'lcommande' => $lcommande, 'form' => $form->createView()));
    }
    public function AjoutLigneDeCommandeAction(Request $request)
    {
        $session = $request->getSession();

        $user=$this->getUser();

        $dateCommande = new \DateTime();
        $dateLivraison = new \DateTime('+2 day');


        $datmax = new DateTime('+ 5 minute');

        $commande = new Commande();

        $this->get('session')->set('datmax', $datmax);

        $em = $this->getDoctrine()->getManager();
        $commande->setDateCommande($dateCommande);
        $commande->setDateMax($datmax);

        $commande->setIdUser($user);
        $em->persist($commande);
        $em->flush();
        $panier = $this->get('session')->get('panier');

        $commande = $em->getRepository( "AppBundle:Commande", $user)->findOneBy(array('idUser' => $user->getId()), array('id' => 'DESC'));
        $prod = $em->getRepository("AppBundle:Velo")->findArray(array_keys($session->get('panier')));
        $i = 0;

        ksort($panier);

        foreach ($prod as $p) {
            $lcommande = new  LigneDeCommande();

            $lcommande->setDateLivraison($dateLivraison);

            $lcommande->setIdVelo($p);
            $lcommande->setPrixTotal(array_values($panier)[$i] * $p->getPrix());
            $lcommande->setQuantite(array_values($panier)[$i]);
            $p->setQuantity($p->getQuantity() - $lcommande->getQuantite());
            $lcommande->setAdresse($this->get('session')->get('addresse'));
            $lcommande->setAdresse2($this->get('session')->get('addresse2'));
            $lcommande->setVille($this->get('session')->get('ville'));
            $lcommande->setCodePostal($this->get('session')->get('codepostal'));
            $lcommande->setNumTel($this->get('session')->get('numtel'));

            $lcommande->setIdCommande($commande);


            $em->persist($lcommande);
            $em->flush();
            $i++;
            $em = $this->getDoctrine()->getManager();
            $commande = $em->getRepository("AppBundle:Commande", $user)->findOneBy(array('idUser' => $user->getId()), array('id' => 'DESC'));
            $message = \Swift_Message::newInstance()
                ->setSubject('Velo Shop')
                ->setFrom('veloshop@zohomail.com')
                ->setTo($this->getUser()->getEmail())
                ->setBody('Nous vous informons que votre commande N° '.$commande->getId().' a été prise en compte, vous avez au maximum 5 heures pour annuler votre commande - merci pour votre achat
            Cordialement l\'equipe CodeBusters ')

            ;
            $this->get('mailer')->send($message);
        }


        unset($panier);
        return $this->redirectToRoute('afficher_commande');

        return $this->render("@Ecommerce/LigneDeCommande/afficheLigneDeCommande.html.twig",
            array('lcommande' => $lcommande));
    }
    public function afficherCommandeAction()
    {
        $user = $this->getUser();


        $em = $this->getDoctrine()->getManager();
        $commande = $em->getRepository("AppBundle:Commande")->findby(array('idUser' => $user->getId()));


        return $this->render('@Ecommerce/LigneDeCommande/afficherCommande.html.twig', array(
            'commande' => $commande));
    }

    public function AfficherLigneDeCommandeAction($id)
    {
        $user = $this->getUser();

        $em = $this->getDoctrine()->getManager();

        $lcommande = $em->getRepository("AppBundle:LigneDeCommande")->findby(array('idCommande' => $id));


        $datesys = new DateTime();


        return $this->render('@Ecommerce/LigneDeCommande/afficheLigneDeCommande.html.twig', array(
            'lst' => $lcommande, 'datesys' => $datesys));
    }
    public function ModifierLigneDeCommandeAction(Request $request, $id)
    {
        $user = $this->getUser();


        $em = $this->getDoctrine()->getManager();

        $lcommande = $em->getRepository("AppBundle:LigneDeCommande", $id)->find($id);

        $form = $this->createForm(LigneDeCommandeType::class, $lcommande);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em->persist($lcommande);
            $em->flush();
            return $this->redirectToRoute('afficher_commande');
        }
        return $this->render("@Ecommerce/LigneDeCommande/modifierLigneDeCommande.html.twig",
            array('form' => $form->createView()));
    }

    public function SupprimerLigneDeCommandeAction(Request $request, $id)
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
        return $this->redirectToRoute('afficher_commande');

        return $this->render("@Ecommerce/LigneDeCommande/afficheLigneDeCommande.html.twig", array('dm' => $dm, 'datesys' => $datesys));
    }
}