<?php

namespace EvenementBundle\Controller;
use AppBundle\Entity\event;
use AppBundle\Entity\Velo;
use AppBundle\Form\eventType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\HttpFoundation\Request;

class EvenementController extends Controller
{

    public function AjouterEventAction(Request $request)
    {  $event = new event();
        $form = $this->createFormBuilder($event)

            ->add('nom')
            ->add('date', DateType::class, array('data' => new \DateTime(), 'widget' => 'single_text'))
            ->add('description')
            ->add('image',FileType::class,array('data_class' => null))
            ->add('lieu')
            ->add('prix')

            ->add('particip')

            ->getForm();

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $Photo = $event->getImage();
            if ($Photo) {
                $fileName = md5(uniqid()) . '.' . $Photo->guessExtension();

                try {
                    $Photo->move(
                        $this->getParameter('Photo_directory'),
                        $fileName
                    );
                } catch (FileException $e) {

                }
                $event->setImage($fileName);
            }
            $event->setNom($form['nom']->getData());
            $event->setDate($form['date']->getData());
            $event->setDescription($form['description']->getData());

            $event->setLieu($form['lieu']->getData());
            $event->setPrix($form['prix']->getData());
            $event->setParticip($form['particip']->getData());

            $em=$this->getDoctrine()->getManager();
            $em->persist($event);
            $em->flush();
           return $this->redirectToRoute('afficher_event');
        }
        return $this->render('@Evenement/ajouterEvent.html.twig', array(
            "form"=>$form->createView()

        ));
    }
 public function AfficherEventAction(Request $request)
    {
        $event=$this->getDoctrine()->getRepository(event::class)->findAll();
        return $this->render('@Evenement/AfficherEvent.html.twig',array('event'=>$event));
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