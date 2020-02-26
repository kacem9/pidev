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


class ReparateurController extends Controller
{
    public function Liste_reparateurAction()
    {

        $em = $this->getDoctrine()->getEntityManager();

        $expr = $em->createQueryBuilder()->expr();
        $query = $em->getRepository('AppBundle:User')->createQueryBuilder('u')
            ->where('u.roles LIKE :bo')
            ->setParameters([
                    'bo' => '%"ROLE_REPARATEUR"%']
            )
            ->getQuery();

        $reparateurs = $query->getResult();


        return $this->render('@Ecommerce/Reparateur/ListeReparateur.html.twig', array('Reparateurs' => $reparateurs));


    }

    public function EspaceRdvAction($id, Request $request)
    {
        $em = $this->getDoctrine()->getEntityManager();
        $User = $em->getRepository('AppBundle:User')->find($id);
        $rendezvous = new rendezvous();

        $form = $this->createForm(rendezvousType::class, $rendezvous);
        $form->handleRequest($request);
        if ($form->isSubmitted()) {

            $rendezvous->setUser($User);
            $user=$this->getUser();
            $rendezvous->setNom($user->getNom());
            $rendezvous->setPrenom($user->getPrenom());
            $rendezvous->setEmail($user->getEmail());
            $rendezvous->setAdresse($user->getAdresse());
            $rendezvous->setNumtel($user->getNumTel());
            $message = \Swift_Message::newInstance()
                ->setSubject('Acknowledgment of receipt')
                ->setFrom('veloshop@zohomail.com')
                ->setTo($rendezvous->getEmail())
                ->setBody(
                        'Appointment received with '. $rendezvous->getPrenom().''. $rendezvous->getNom() .''.$rendezvous->getMessage() );

            $em->persist($rendezvous);
            $em->flush();
            return $this->redirectToRoute('Liste_reparateur');
        }
        return $this->render('@Ecommerce/Reparateur/EspaceRdv.html.twig', array('form' => $form->createView()));
    }

    public function listrendezvousAction()
    {
        $em = $this->getDoctrine()->getEntityManager();
        $currentUser = $this->getuser()->getEmail();

        $rendezvous = $em->getRepository('AppBundle:rendezvous')->findByEmail($currentUser);

        return $this->render('@Ecommerce/Reparateur/listrendezvous.html.twig', array('listrendezvous' => $rendezvous));


    }
    public function MaRendezVousValidAction()
    {

        $em = $this->getDoctrine()->getEntityManager();
        $currentUser = $this->getuser()->getEmail();

        $listrendezvous = $em->getRepository('AppBundle:validrendezvous')->findByEmailR( $currentUser);

        return $this->render('@Ecommerce/Reparateur/MaRendezVousValid.html.twig', array('listrendezvous' => $listrendezvous));

    }
    public function SupprimesAction(Request $request, $Cin)
    {

        $em = $this->getDoctrine()->getEntityManager();
        $listrendezvous  = $em->getRepository('AppBundle:rendezvous')->find($Cin);
        if (!$listrendezvous ) {
            throw $this->createNotFoundException('No list rendez vous valid found for id '.$Cin);
        }
        $em->remove($listrendezvous );
        $em->flush();
        $request->getSession()->getFlashBag()->add('success', ' Delete done');
        return $this->redirect($this->generateUrl('listrendezvous'));
    }
    public function ModifirendezvousAction($Cin,Request $request){
        $em=$this->getDoctrine()->getManager();
        $rendezvous=$em->getRepository(rendezvous ::class)->find($Cin);
        $form=$this->createForm( rendezvousType::class,$rendezvous);
        $form->handleRequest($request);
        if ($form->isSubmitted()){
            $em=$this->getDoctrine()->getManager();
            $em->flush();
            return $this->redirectToRoute('listrendezvous');
        }
        return $this->render('@Ecommerce/Reclamation/Modifirendezvous.html.twig',array('form' => $form->createView()));
    }
    public function SupprimlAction(Request $request, $reference)
    {

        $em = $this->getDoctrine()->getEntityManager();
        $listrendezvous  = $em->getRepository('AppBundle:validrendezvous')->find($reference);
        if (!$listrendezvous ) {
            throw $this->createNotFoundException('No list rendez vous valid found for id '.$reference);
        }
        $em->remove($listrendezvous );
        $em->flush();
        $request->getSession()->getFlashBag()->add('success', ' Delete done');
        return $this->redirect($this->generateUrl('MaRendezVousValid'));
    }
}