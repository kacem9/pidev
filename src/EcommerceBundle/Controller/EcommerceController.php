<?php


namespace EcommerceBundle\Controller;


use AppBundle\Entity\Commentaire;
use AppBundle\Entity\Event;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\HttpFoundation\Request;

class EcommerceController extends Controller
{
    public function indexAction()
    {
        $em = $this->getDoctrine()->getEntityManager();
        $actualites = $em->getRepository('AppBundle:Actualite')->findAll();

        return $this->render('@Ecommerce/index.html.twig',array('actualites'=>$actualites));
    }
    public function AfficherListeEventAction(Request $request)
    {
        $event=new Event();
        $em=$this->getDoctrine()->getManager();
        $form=$this->createFormBuilder($event)
            ->add('nom')
            ->add('Recherche', SubmitType::class , ['attr'=>['formvalidate'=>'formvalidate']])
            ->getForm();
        $form->handleRequest($request);
        if($form->isSubmitted()) {
            $event=$em->getRepository(Event ::class)->findBy(array('nom' => $event->getNom()));
        }
        else{
            $event=$em->getRepository(Event ::class)->findBy([
                'etat'=>1
            ]);
        }
        return $this->render('@Ecommerce/event/AfficherEvent.html.twig',array('form'=>$form->createView(),'event'=>$event));
    }
    public function detailsEventAction(Request $request,$id)
    {
        $em = $this->getDoctrine()->getEntityManager();
        $event = $em->getRepository('AppBundle:Event')->find($id);

        $c=new Commentaire();
        $form = $this->createFormBuilder($c)
            ->add('contenu',TextareaType::class)
            ->getForm();
        $form->handleRequest($request);
        $em = $this->getDoctrine()->getManager();
        $com = $em->getRepository('AppBundle:Commentaire')->findAll();
        if ($form->isValid() && $form->isSubmitted()) {
            $c->setUser($this->getUser());
            $c->setDate(new \DateTime());
            $em = $this->getDoctrine()->getManager();
            $em->persist($c);
            $em->flush();
        }
        return $this->render('EcommerceBundle:event:DetailsEvent.html.twig',array('event'=>$event,
            "form"=>$form->createView(),'com' => $com));
    }

    public function ModifierCommentaireAction(Request $request)
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
            return $this->redirectToRoute("details_events");

        }
        return $this->render('EcommerceBundle:event:ModifierCommentaire.html.twig', array("form" => $form->createView()

        ));
    }
    public function SupprimerCommentaireAction($id_com)
    {
        $em = $this->getDoctrine()->getManager();
        $commentaire = $em->getRepository('AppBundle:Commentaire')->find($id_com);
        $em->remove($commentaire);
        $em->flush();
        return $this->redirectToRoute("details_events");

    }

}