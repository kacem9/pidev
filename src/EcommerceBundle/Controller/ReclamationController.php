<?php


namespace EcommerceBundle\Controller;
use AppBundle\Entity\Actualite;
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
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
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


class ReclamationController extends Controller
{
    function reclamationAction(Request $request){
        $em=$this->getDoctrine()->getManager();
        $currentUser = $this->getuser();
        $reclamation=new reclamation();
        $form=$this->createForm(reclamationType::class,$reclamation);
        $form->handleRequest($request);//cree un copie du formulaire dans le cash

        if($form->isSubmitted())
        { $reclamation->setUser($currentUser);
            $user=$this->getUser();
            $reclamation->setCin($user->getCin());
            $reclamation->setNom($user->getNom());
            $reclamation->setPrenom($user->getPrenom());
            $reclamation->setEmail($user->getEmail());

            $reclamation->setNumtel($user->getNumTel());
            $em->persist($reclamation);
            $em->flush();
            return $this->redirectToRoute('mesreclamation');

        }
        return $this->render('@Ecommerce/Reclamation/reclamation.html.twig',array('form'=>$form->createView()));
    }
    public function mesreclamationAction(){
        $em=$this->getDoctrine()->getManager();
        $user = $this->container->get('security.token_storage')->getToken()->getUser()->getId();

        $reclamation = $em->getRepository('AppBundle:reclamation')->findByUser($user);

        return $this->render('@Ecommerce/Reclamation/mesreclamation.html.twig',array('reclamation'=>$reclamation));
    }
    public function SuppreclamationAction(Request $request, $reference)
    {

        $em = $this->getDoctrine()->getEntityManager();
        $reclamation = $em->getRepository('AppBundle:reclamation')->find($reference);
        if (!$reclamation) {
            throw $this->createNotFoundException('No reclamation found for id '.$reference);
        }
        $em->remove($reclamation);
        $em->flush();
        $request->getSession()->getFlashBag()->add('success', ' Delete done ');
        return $this->redirect($this->generateUrl('mesreclamation'));
    }


    public function ModifireclamationAction($reference,Request $request){
        $em=$this->getDoctrine()->getManager();
        $reclamation=$em->getRepository(reclamation ::class)->find($reference);
        $form=$this->createForm( reclamationType::class,$reclamation);
        $form->handleRequest($request);
        if ($form->isSubmitted()){
            $em=$this->getDoctrine()->getManager();
            $em->flush();
            return $this->redirectToRoute('mesreclamation');
        }
        return $this->render('@Ecommerce/Reclamation/Modifireclamation.html.twig',array('form' => $form->createView()));
    }

}