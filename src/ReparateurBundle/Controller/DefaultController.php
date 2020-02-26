<?php

namespace ReparateurBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use AppBundle\Entity\Actualite;
use AppBundle\Entity\Comment;
use AppBundle\Entity\Event;
use AppBundle\Entity\reclamation;
use AppBundle\Entity\rendezvous;
use AppBundle\Entity\User;
use AppBundle\Entity\validrendezvous;
use AppBundle\Form\reclamationType;
use AppBundle\Form\rendezvousType;
use AppBundle\Form\validrendezvousType;
use MyAppMailBundle\Entity\Mail;
use MyAppMailBundle\Form\MailType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CountryType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Gregwar\CaptchaBundle\GregwarCaptchaBundle;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\Request;
use Gregwar\CaptchaBundle\Type\CaptchaType;
use Symfony\Component\HttpFoundation\Response;
class DefaultController extends Controller
{
    public function indexAction()
    {


        $em = $this->getDoctrine()->getEntityManager();
        $actualites = $em->getRepository('AppBundle:Actualite')->findAll();

        $Comment = $em->getRepository('AppBundle:Comment')->findAll();



        return $this->render('@Reparateur/index.html.twig',array('actualites'=>$actualites,'Comment'=>$Comment));


    }
    public function CommentActAction(Request $request )

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
            return $this->redirectToRoute('reparateur_homepage');
        }
        return $this->render('@Reparateur/Default/Comment.html.twig', array(
            "form"=>$form->createView()

        ));
    }

    public function ajouterUneEtoileAction($id,$nombre,Request $request){
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
        return $this->render('@Reparateur/index.html.twig',array('actualites'=>$actualites ,'Comment'=>$Comment));

    }




    public function espacereparateurAction()
    {
        $em = $this->getDoctrine()->getEntityManager();
        $user = $this->container->get('security.token_storage')->getToken()->getUser()->getId();

        $rendezvous = $em->getRepository('AppBundle:rendezvous')->findByUser($user);

        return $this->render('@Reparateur/Default/EspaceReparateur.html.twig', array('rendezvous' => $rendezvous));


    }

    public function validrendezvousAction($id, Request $request)
    { $em=$this->getDoctrine()->getManager();
        $currentUser = $this->getUser();
        $rendezvous=$em->getRepository(rendezvous::class)->find($id);
        $validrendezvous = new validrendezvous();
        $Form = $this->createForm(validrendezvousType::class, $validrendezvous);
        $Form->handleRequest($request);
        if ($Form->isSubmitted()) {
            $validrendezvous->setUser($currentUser);
            $Utilisateur = $this->getDoctrine()->getRepository(rendezvous::class)->find($id);
            $email = $Utilisateur->getEmail();
            $validrendezvous->setEmailR($email);


            $message = \Swift_Message::newInstance()
                ->setSubject('Velo Shop')
                ->setFrom('veloshop@zohomail.com')

                ->setTo($validrendezvous->getEmailR())
                ->setBody( $validrendezvous->getMessage() );
            $this->get('mailer')->send($message);



            $em->persist($validrendezvous);
            $em->remove($rendezvous);
            $this->getDoctrine()->getManager()->flush();
            return $this->redirectToRoute('espacereparateur');
        }
        return $this->render('@Reparateur/Default/validrendezvous.html.twig', array('form' => $Form->createView()));
    }

    public function refuserendezvousrAction($id, Request $request)
    {
        $currentUser=$this->getUser();
        $em = $this->getDoctrine()->getEntityManager();
        $rendezvous=$em->getRepository(rendezvous ::class)->find($id);


        $Utilisateur=$this->getDoctrine()->getRepository(rendezvous::class)->find($id);
        $email=$Utilisateur->getEmail();
        $rendezvous->setEmail($email);



        $message = \Swift_Message::newInstance()
            ->setSubject('Velo Shop')
            ->setFrom('veloshop@zohomail.com')
            ->setTo($rendezvous->getEmail())
            ->setBody(

                  'Unfortunately, I regret to inform you that I will not honor this appointment');
        $this->get('mailer')->send($message);




        $this->getDoctrine()->getManager()->persist($rendezvous);
        $em->remove($rendezvous);

        $this->getDoctrine()->getManager()->flush();
        return $this->redirectToRoute('espacereparateur');


    }

    public function delAction(Request $request, $cin)
    {

        $em = $this->getDoctrine()->getEntityManager();
        $rendezvous = $em->getRepository('AppBundle:rendezvous')->find($cin);
        if (!$rendezvous) {
            throw $this->createNotFoundException('No Reparateurs found for id '.$cin);
        }
        $em->remove($rendezvous);
        $em->flush();
        $request->getSession()->getFlashBag()->add('success', ' Delete done');
        return $this->redirect($this->generateUrl('espacereparateur'));
    }
}
