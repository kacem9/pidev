<?php


namespace EcommerceBundle\Controller;


use AppBundle\Entity\CommentaireVelo;
use AppBundle\Form\CommentaireVeloType;
use LocalBundle\Entity\Likes;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class PaiementController extends Controller
{
    public function menuAction()
    {
        $session = $this->getRequest()->getSession();
        if (!$session->has('panier'))
            $articles = 0;
        else
            $articles = count($session->get('panier'));

        return $this->render('@Ecommerce/Panier/panier.html.twig', array('articles' => $articles));
    }


    public function AfficherpannierAction(Request $request,$id)
    {
        $em = $this->getDoctrine()->getEntityManager();
        $velo = $em->getRepository('AppBundle:Velo')->findById($id);
        $velos = $em->getRepository('AppBundle:Velo')->find($id);


        $form = $this->createForm(CommentaireVeloType::class);
        $form->handleRequest($request);
        $contenu = $form['contenu']->getData();

        if ($form->isValid() && $form->isSubmitted()) {

            $commentaire = new CommentaireVelo();
            $commentaire->setIdVelo($velos);
            $commentaire->setIdUser($this->getUser());
            $commentaire->setContenu($contenu);
            $commentaire->setDate(new \DateTime());
            $em->persist($commentaire);
            $em->flush();
        }

        $commentaires = $em->getRepository("AppBundle:CommentaireVelo")->findBy(array('idVelo' => $id));

        return $this->render('@Ecommerce/Panier/Paiement.html.twig',array('velo' => $velo,'commentaires' => $commentaires,'form' => $form->createView(),


            ));
    }
    public function DeleteCommentaireAction($id)
    {
        $em=$this->getDoctrine()->getManager();
        $commentaire=$em->getRepository("AppBundle:CommentaireVelo")->find($id);

        if (!$commentaire) {
            throw $this->createNotFoundException('No demande found for id '.$id);

        }
        $idVelo=$commentaire->getIdVelo()->getId();
        $em->remove($commentaire);
        $em->flush();
        return $this->redirectToRoute('Afficherpannier', array('id'=>$idVelo));
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



        return $this->redirect($this->generateUrl('panier'));

    }

    public function panierAction(Request $request)
    {
        $session = $request->getSession();

        if (!$session->has('panier')) $session->set('panier', array());


        $em = $this->getDoctrine()->getManager();
        $produits = $em->getRepository('AppBundle:Velo')->findById(array_keys($session->get('panier')));

        return $this->render('@Ecommerce/Panier/afficherPanier.html.twig', array('produits' => $produits,
            'panier' => $session->get('panier')));

    }
    public function supprimerAction(Request $request,$id)
    {
        $session = $request->getSession();
        $panier = $session->get('panier');

        if (array_key_exists($id, $panier))
        {
            unset($panier[$id]);
            $session->set('panier',$panier);
            $this->get('session')->getFlashBag()->add('success','Article supprimé avec succès');
        }

        return $this->redirect($this->generateUrl('panier'));
    }


    }