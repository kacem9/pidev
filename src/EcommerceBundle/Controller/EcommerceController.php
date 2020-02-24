<?php


namespace EcommerceBundle\Controller;


use AppBundle\Entity\Commentaire;
use AppBundle\Entity\Event;
use AppBundle\Entity\Participation;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

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
            ->add('Search', SubmitType::class , ['attr'=>['formvalidate'=>'formvalidate']])
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
        $part=$em->getRepository(Participation ::class)->findBy([
            'user'=>$this->getUser()
        ]);

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
            return $this->redirectToRoute("ecommerce_listevent");
        }
        return $this->render('@Ecommerce/event/AfficherEvent.html.twig',array('form'=>$form->createView(),'event'=>$event,'part'=>$part,
            "f"=>$form->createView(),'com' => $com));
    }
    public function detailsEventAction($id)
    {
        $em = $this->getDoctrine()->getEntityManager();
        $event = $em->getRepository('AppBundle:Event')->find($id);

        return $this->render('EcommerceBundle:event:DetailsEvent.html.twig',array('event'=>$event));
    }

    public function ModifierCommentaireAction(Request $request,$id_com)
    {
        $em=$this->getDoctrine()->getManager();
        $commentaire=$em->getRepository(Commentaire ::class)->find($id_com);
        $form = $this->createFormBuilder($commentaire)
            ->add('contenu',TextareaType::class)
            ->getForm();
        $form->handleRequest($request);
        if ($form->isSubmitted()){
            $em=$this->getDoctrine()->getManager();
            $em->flush();
            return $this->redirectToRoute('ecommerce_listevent');
        }
        return $this->render('@Ecommerce/event/ModifierCommentaire.html.twig',array('form'=>$form->createView()));
    }


    public function SupprimerCommentaireAction($id_com)
    {
        $em = $this->getDoctrine()->getManager();
        $com = $em->getRepository(Commentaire::class)->find($id_com);
        $em->remove($com);
        $em->flush();
        return $this->redirectToRoute("ecommerce_listevent");

    }
    public function ParticiperAction(Request $request,$id)
    {
        $part=new Participation();
        $Evenement=$this->getDoctrine()->getRepository(Event::class)->find($id);
        $nb=$Evenement->getNbrParticipant();

        $Evenement->setNbrParticipant($nb-1);
        $part->setEvent($Evenement);
        $part->setUser($this->getUser());
        $this->getDoctrine()->getManager()->persist($part);
        $this->getDoctrine()->getManager()->flush();
        return $this->redirectToRoute("ecommerce_listevent");
    }
    public function AnnulerParticipationAction($id_participation)
    {
        $em = $this->getDoctrine()->getManager();
        $part = $em->getRepository(Participation::class)->find($id_participation);
        $em->remove($part);
        $em->flush();
        return $this->redirectToRoute("ecommerce_listevent");
    }
    public function DateascAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $repository = $em->getRepository(Event::class);



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
//            $event=$em->getRepository(Event ::class)->findBy([
//                'etat'=>1
//            ]);
            $query = $em->createQueryBuilder()
                ->select('v')->from('AppBundle:Event', 'v')
                ->orderBy('v.dateEvent', 'ASC')
                ->where('v.etat = 1')
                ->getQuery();

            $event = $query->getResult();
        }
        $part=$em->getRepository(Participation ::class)->findBy([
            'user'=>$this->getUser()
        ]);

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
            return $this->redirectToRoute("ecommerce_listevent");
        }
        return $this->render('@Ecommerce/event/AfficherEvent.html.twig',array('form'=>$form->createView(),'event'=>$event,'part'=>$part,
            "f"=>$form->createView(),'com' => $com));

    }
    public function DatedescAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $repository = $em->getRepository(Event::class);



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
//            $event=$em->getRepository(Event ::class)->findBy([
//                'etat'=>1
//            ]);
            $query = $em->createQueryBuilder()
                ->select('v')->from('AppBundle:Event', 'v')
                ->orderBy('v.dateEvent', 'DESC')
                ->where('v.etat = 1')
                ->getQuery();

            $event = $query->getResult();
        }
        $part=$em->getRepository(Participation ::class)->findBy([
            'user'=>$this->getUser()
        ]);

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
            return $this->redirectToRoute("ecommerce_listevent");
        }
        return $this->render('@Ecommerce/event/AfficherEvent.html.twig',array('form'=>$form->createView(),'event'=>$event,'part'=>$part,
            "f"=>$form->createView(),'com' => $com));
    }
    public function searchAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $requestString = $request->get('q');
        $event =  $em->getRepository('AppBundle:Event')->findEntitiesByString($requestString);

        if(!$requestString)
        {
            $event=$em->getRepository(Event ::class)->findBy([
                'etat'=>1
            ]);
            $result['Event'] = $this->getRealEntities($event);

        }
        else {
            $result['Event'] = $this->getRealEntities($event);
        }

        dump($result['Event']);
        return new Response(json_encode($result));
    }
    public function getRealEntities($event){
        foreach ($event as $event){
            $realEntities[$event->getId()] = [$event->getNom(),$event->getDateEvent()];
        }
        return $realEntities;
    }
}