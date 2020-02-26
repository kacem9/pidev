<?php

namespace EvenementBundle\Controller;

use AppBundle\Entity\Actualite;
use AppBundle\Entity\Comment;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class DefaultController extends Controller
{
    public function actualiteAction()
    {
        $em = $this->getDoctrine()->getEntityManager();
        $actualites = $em->getRepository('AppBundle:Actualite')->findAll();
        $Comment = $em->getRepository('AppBundle:Comment')->findAll();
        return $this->render('@Evenement/index.html.twig',array('actualites'=>$actualites,'Comment'=>$Comment));
    }
    public function CommentActEAction(Request $request )

    {

        $em = $this->getDoctrine()->getEntityManager();

        $currentUser = $this->getuser();

        $Comment = new Comment();
        $Comment->setDate(new \DateTime('now'));
        $form = $this->createFormBuilder($Comment)

            ->add('commentaires')



            ->getForm();

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {


            $Comment->setUser($currentUser);



            $user=$this->getUser();



            $Comment->setCommentaires($form['commentaires']->getData());





            $em=$this->getDoctrine()->getManager();

            $em->persist($Comment);
            $em->flush();
            return $this->redirectToRoute('evenement_homepage');
        }
        return $this->render('@Evenement/event/Comment.html.twig', array(
            "form"=>$form->createView()

        ));
    }

    public function ajouterUneEtoileEAction($id,$nombre,Request $request){
        $em = $this->getDoctrine()->getEntityManager();
        $actualites = $em->getRepository('AppBundle:Actualite')->findAll();
        $actualite=$this->getDoctrine()->getRepository(Actualite::class)->find($id);
        $nbrLike=$actualite->getNbrLike();
        $nbrFoisLike=$actualite->getNbrFoisLike();
        $nbrLike=$nbrLike+$nombre;
        $nbrFoisLike=$nbrFoisLike+1;
        $moyenne=$nbrLike/$nbrFoisLike;
        $actualite->setMoyenneLike($moyenne);
        $actualite->setNbrLike($nbrLike);
        $actualite->setNbrFoisLike($nbrFoisLike);


        $Comment = $em->getRepository('AppBundle:Comment')->findAll();
        $this->getDoctrine()->getManager()->persist($actualite);
        $this->getDoctrine()->getManager()->flush();
        return $this->render('@Evenement/index.html.twig',array('actualites'=>$actualites ,'Comment'=>$Comment));

    }
}
