<?php


namespace CommentaireBundle\Controller;

use AppBundle\Entity\Commentaire;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\HttpFoundation\Request;

class CommentaireController extends Controller
{
    public function AjouterCommentairesAction(Request $request)
    {
        $commentaire=new Commentaire();
        $form = $this->createFormBuilder($commentaire)
            ->add('contenu',TextareaType::class)
            ->getForm();
        $form->handleRequest($request);
        $em = $this->getDoctrine()->getManager();
        $commentaires = $em->getRepository('AppBundle:Commentaire')->findAll();
        if ($form->isValid() && $form->isSubmitted()){
            $commentaire->setUser($this->getUser());
            $commentaire->setDate(new \DateTime());
            $em = $this->getDoctrine()->getManager();
            $em->persist($commentaire);
            $em->flush();
            return $this->redirectToRoute("ajouter_commentaire");
        }
        return $this->render('CommentaireBundle:commentaire:AjouterCommentaire.html.twig', array("form"=>$form->createView(),"commentaires" => $commentaires
        ));
    }
   // public function AfficherCommentaireAction()
   // {
     //   $em = $this->getDoctrine()->getManager();
   //     $commentaire = $em->getRepository('AppBundle:Commentaire')->findAll();
    //    return $this->render('CommentaireBundle:Commentaire:AfficherCommentaire.html.twig', array("commentaire" => $commentaire));
   // }
    public function ModifierCommentairesAction(Request $request)
    {
        $id_com = $request->get("id_com");
        $em = $this->getDoctrine()->getManager();
        $commentaire = $em->getRepository("AppBundle:Commentaire")->find($id_com);
        $form = $this->createFormBuilder($commentaire)
            ->add('contenu',TextareaType::class)
            ->getForm();
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $commentaire->setUser($this->getUser());
            $commentaire->setDate(new \DateTime());
            $em = $this->getDoctrine()->getManager();
            $em->persist($commentaire);
            $em->flush();
            return $this->redirectToRoute("ajouter_commentaire");

        }
        return $this->render('CommentaireBundle:Commentaire:ModifierCommentaire.html.twig', array("form" => $form->createView()

        ));
    }
    public function SupprimerCommentairesAction($id_com)
    {
        $em = $this->getDoctrine()->getManager();
        $commentaire = $em->getRepository('AppBundle:Commentaire')->find($id_com);
        $em->remove($commentaire);
        $em->flush();
        return $this->redirectToRoute("ajouter_commentaire");

    }
}