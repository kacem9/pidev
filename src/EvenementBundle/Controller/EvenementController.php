<?php

namespace EvenementBundle\Controller;
use AppBundle\Entity\Event;
use AppBundle\Form\EventType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
class EvenementController extends Controller
{

    public function AjouterEventAction(Request $request)
    {
        $event = new Event();
        $form = $this->createFormBuilder($event)

            ->add('nom')
            ->add('dateEvent', DateType::class, array('data' => new \DateTime(), 'widget' => 'single_text'))
            ->add('description')
            ->add('photo',FileType::class,array('data_class' => null))
            ->add('lieuEvent')
            ->add('prix')
            ->add('categories_Event', EntityType::class, array(
                'class' => 'AppBundle:Categories_Event',
                'choice_label' => 'type',
                'mapped' => false
            ))
            ->add('nbrParticipant')

            ->getForm();

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $photo = $event->getPhoto();
            if ($photo) {
                $fileName = md5(uniqid()) . '.' . $photo->guessExtension();

                try {
                    $photo->move(
                        $this->getParameter('Photo_directory'),
                        $fileName
                    );
                } catch (FileException $e) {

                }
                $event->setPhoto($fileName);
            }

            $event->setNom($form['nom']->getData());
            $event->setDateEvent($form['dateEvent']->getData());
            $event->setDescription($form['description']->getData());
            $event->setCategoriesEvent($form['categories_Event']->getData());
            $event->setLieuEvent($form['lieuEvent']->getData());
            $event->setPrix($form['prix']->getData());
            $event->setNbrParticipant($form['nbrParticipant']->getData());
            $event->setEtat(0);

            $em=$this->getDoctrine()->getManager();
            $em->persist($event);
            $em->flush();
            return $this->redirectToRoute('afficher_event');
        }
        return $this->render('@Evenement/event/ajouterEvent.html.twig', array(
            "form"=>$form->createView()

        ));
    }

    public function SupprimerEventAction($id){
        $em=$this->getDoctrine()->getManager();
        $event=$em->getRepository(Event::class)->find($id);
        $em->remove($event);
        $em->flush();
        return $this->redirectToRoute('afficher_event');
    }
    public function ModifierEventAction($id,Request $request){
        $em=$this->getDoctrine()->getManager();
        $event=$em->getRepository(Event ::class)->find($id);
        $form=$this->createFormBuilder($event)
            ->add('nom')
            ->add('dateEvent', DateType::class, array('data' => new \DateTime(), 'widget' => 'single_text'))
            ->add('description')
            ->add('photo',FileType::class,array('data_class' => null))
            ->add('lieuEvent')
            ->add('prix')
            ->add('nbrParticipant')
            ->add('categories_Event', EntityType::class, array(
                'class' => 'AppBundle:Categories_Event',
                'choice_label' => 'type',
                'mapped' => false
            ))
            ->getForm();
        $form->handleRequest($request);
        if ($form->isSubmitted()){
            $em=$this->getDoctrine()->getManager();
            $em->flush();
            return $this->redirectToRoute('afficher_event');
        }
        return $this->render('@Evenement/event/ModifierEvent.html.twig',array('form'=>$form->createView()));
    }
    public function AfficherEventAction(Request $request){
        $event=new Event();
        $em=$this->getDoctrine()->getManager();
        $form=$this->createFormBuilder($event)
            ->add('nom')
            ->add('Search', SubmitType::class , ['attr'=>['formvalidate'=>'formvalidate']])
            ->getForm();
        $form->handleRequest($request);
        if($form->isSubmitted()) {
            $event=$em->getRepository(Event ::class)->findBy(array('nom' => $event->getNom()));
        }
        else{
            $event=$em->getRepository(Event ::class)->findAll();
        }
        return $this->render('@Evenement/event/AfficherEvent.html.twig',array('form'=>$form->createView(),'event'=>$event));
    }
    public function detailsEventAction($id)
    {
        $em = $this->getDoctrine()->getEntityManager();
        $event = $em->getRepository('AppBundle:Event')->find($id);

        return $this->render('EvenementBundle:event:DetailsEvent.html.twig',array('event'=>$event));
    }
    public function pdfAction()
    {
        $liste_event=$this->getDoctrine()->getRepository(Event::class)->findAll();
        $snappy=$this->get('knp_snappy.pdf');
        $file_name="Liste event";
        return new Response(
            $snappy->getOutputFromHtml($this->renderView('@Evenement/event/Pdf.html.twig',array("liste"=>$liste_event))),
            200,
            array(
                'Content-Type'=>'application/pdf',
                'Content-Disposition'=>'attachement; filename="'.$file_name.'.pdf'
            )
        );
    }

}
