<?php

namespace EvenementBundle\Controller;
use AppBundle\Entity\event;
use AppBundle\Form\eventType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class EvenementController extends Controller
{

    public function AjouterEventAction(Request $request)
    {
        $event=new Event();
        $form=$this->createForm(EventType::class,$event);
        $form->handleRequest($request);
        if($form->isValid()){
            $dir="C:\xampp\htdocs\projet\symfony\Velo\web\ImageEvent";
            $file=$form['image']->getData();
            $event->setImage($event->getNom().".png,.jpg,.gif");
            $file->move($dir,$event->getImage());
            $event->setUser($this->getUser());
            $em=$this->getDoctrine()->getManager();
            $em->persist($event);
            $em->flush();
           return $this->redirectToRoute('afficher_event');
        }
        return $this->render('@Evenement/ajouterEvent.html.twig', array(
            "f"=>$form->createView()

        ));
    }
 public function AfficherEventAction(Request $request)
    {
        $event=$this->getDoctrine()->getRepository(event::class)->findAll();
        return $this->render('@Evenement/AfficherEvent.html.twig',array('e'=>$event));
    }
    public function SupprimerEventAction($cin){
        $em=$this->getDoctrine()->getManager();
        $event=$em->getRepository(event ::class)->find($cin);
        $em->remove($event);
        $em->flush();
        return $this->redirectToRoute('afficher_event');
    }
    public function ModifierEventAction($cin,Request $request){
        $em=$this->getDoctrine()->getManager();
        $event=$em->getRepository(event ::class)->find($cin);
        $Form=$this->createForm( eventType::class,$event );
        $Form->handleRequest($request);
        if ($Form->isSubmitted()){
            $em=$this->getDoctrine()->getManager();
            $em->flush();
            return $this->redirectToRoute('afficher_event');
        }
        return $this->render('@Evenement/ModifierEvent.html.twig',array('f'=>$Form->createView()));
    }
}