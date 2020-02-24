<?php

namespace UserBundle\Controller;

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

class DefaultController extends Controller
{
    public function indexAction()
    {

        $event=$this->getDoctrine()->getRepository(Event::class)->findBy([
            'etat'=>1
        ]);
        return $this->render('@User/Default/index.html.twig',array('event'=>$event));
    }


    public function inscriptionAction(Request $request)
    {
        $user = new User();
        $form = $this->createFormBuilder($user)
            ->add('Cin', TextType::class)
            ->add('Nom', TextType::class, array('attr' => ['pattern' => '[a-zA-Z]*']))
            ->add('Prenom', TextType::class)
            ->add('Num_tel', TextType::class)
            ->add('Sexe', ChoiceType::class, array('choices' => array('Homme' => 'Homme', 'Femme' => 'Femme')))
            ->add('Date_naissance', DateType::class, array('data' => new \DateTime(), 'widget' => 'single_text'))
            ->add('Adresse', TextType::class)
            ->add('Poste', TextType::class, array('attr' => ['pattern' => '[a-zA-Z]*']))
            ->add('Civilite', ChoiceType::class, array('choices' => array('Monsieur' => 'Monsieur', ' Madame' => ' Madame', 'Mademoiselle' => 'Mademoiselle')))
            ->add('Pays', CountryType::class, array('attr' => ['pattern' => '[a-zA-Z]*']))
            ->add('Ville', TextType::class, array('attr' => ['pattern' => '[a-zA-Z]*']))
            ->add('Code_postal', TextType::class)
            ->add('Photo', FileType::class, array('data_class' => null, 'required' => True))
            ->add('roles', ChoiceType::class, array('multiple' => true, 'choices' => array('Admin' => 'ROLE_ADMIN', 'Acheteur' => 'ROLE_ACHETEUR', 'Vendeur' => 'ROLE_VENDEUR', 'Chef d equipe' => 'ROLE_CHEF_EQUIPE', 'Reparateur' => 'ROLE_Reparateur')))
            ->add('Username', TextType::class, array('attr' => ['pattern' => '[a-zA-Z]*']))
            ->add('Email', EmailType::class)
            ->add('Password', PasswordType::class, ['required' => True])
            ->add('Entrecode', CaptchaType::class)
            ->getForm();

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $Photo = $user->getPhoto();
            if ($Photo) {
                $fileName = md5(uniqid()) . '.' . $Photo->guessExtension();

                try {
                    $Photo->move(
                        $this->getParameter('Photo_directory'),
                        $fileName
                    );
                } catch (FileException $e) {

                }
                $user->setPhoto($fileName);
            }


            $user->setCin($form['Cin']->getData());
            $user->setNom($form['Nom']->getData());
            $user->setPrenom($form['Prenom']->getData());
            $user->setNumtel($form['Num_tel']->getData());
            $user->setSexe($form['Sexe']->getData());
            $user->setDateNaissance($form['Date_naissance']->getData());
            $user->setAdresse($form['Adresse']->getData());
            $user->setPoste($form['Poste']->getData());
            $user->setCivilite($form['Civilite']->getData());
            $user->setPays($form['Pays']->getData());
            $user->setVille($form['Ville']->getData());
            $user->setCodepostal($form['Code_postal']->getData());
            $user->setPhoto($fileName);
            $user->setRoles($form['roles']->getData());

            $user->setUsername($form['Username']->getData());
            $user->setEmail($form['Email']->getData());
            $user->setPlainPassword($form['Password']->getData());
            $user->setEnabled(true);
            $em = $this->getDoctrine()->getEntityManager();


            $em->persist($user);
            $em->flush();
            return $this->redirect($this->generateUrl('user_page_homepage'));


        }
        return $this->render('@User/Default/inscription.html.twig', array('form' => $form->createView()));
    }

    public function EventAction(Request $request){
        $event=new Event();
        $em=$this->getDoctrine()->getManager();
        $form=$this->createFormBuilder($event)
            ->add('nom' )
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

        return $this->render('@User/Default/Event.html.twig',array('form'=>$form->createView(),'event'=>$event));
    }
    public function EspaceAcheteurAction()
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


        return $this->render('@User/Default/EspaceAcheteur.html.twig', array('Reparateurs' => $reparateurs));


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
                ->setSubject('Accusé de réception')
                ->setFrom('nesrine.zouaoui1@esprit.tn')
                ->setTo($rendezvous->getEmail())
                ->setBody(
                    $this->renderView('@MyAppMail/Mail/mail.html.twig',
                        array('prenom' => $rendezvous->getPrenom() ,'nom' => $rendezvous->getNom() ,'message' => $rendezvous->getMessage() ,  'numtel' => $rendezvous->getNumtel(),'adresse' => $rendezvous->getAdresse(), 'typepanne' => $rendezvous->getTypepanne(), 'email' => $rendezvous->getEmail() )) ,'text/html');
            $this->get('mailer')->send($message);

            $em->persist($rendezvous);
            $em->flush();
            return $this->redirectToRoute('EspaceAcheteur');
        }
        return $this->render('@User/Default/EspaceRdv.html.twig', array('form' => $form->createView()));
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
        return $this->redirect($this->generateUrl('EspaceReparateur'));
    }























    public function EspaceReparateurAction()
    {
        $em = $this->getDoctrine()->getEntityManager();
        $user = $this->container->get('security.token_storage')->getToken()->getUser()->getId();

        $rendezvous = $em->getRepository('AppBundle:rendezvous')->findByUser($user);

        return $this->render('@User/Default/EspaceReparateur.html.twig', array('rendezvous' => $rendezvous));


    }




    public function getRealEntities($rendezvous){
        foreach ($rendezvous as $rendezvous){
            $realEntities[$rendezvous->getId()] = [$rendezvous>getNom(),$rendezvous->getPrenom()];

        }
        return $realEntities;
    }


    public function indexxAction(Request $request)
    {
        $mail = new Mail();
        $form = $this->createForm(MailType::class, $mail);
        $form->handleRequest($request);
        if ($form->isSubmitted()) {

            $message = \Swift_Message::newInstance()
                ->setSubject('Accusé de réception')
                ->setFrom('nesrine.zouaoui1@esprit.tn')
                ->setTo($mail->getEmail())
                ->setBody(
                    $this->renderView('@User/Default/mail.html.twig',
                        array('nom' => $mail->getNom(), 'prenom' => $mail->getPrenom())), 'text/html');
            $this->get('mailer')->send($message);
            return $this->redirect($this->generateUrl('mail'));
        }
        return $this->render('@User/Default/Reparateurs.html.twig', array('form'=>$form ->createView()));
    }

    public function mailAction(){
        return new Response("email envoyé avec succès, Merci de vérifier votre adresse mail .");
    }




    public function validrendezvousAction($id, Request $request)
    {
        $currentUser=$this->getUser();
        $validrendezvous = new validrendezvous();
        $Form = $this->createForm(validrendezvousType::class, $validrendezvous);
        $Form->handleRequest($request);
        if ($Form->isSubmitted()) {
            $validrendezvous->setUser($currentUser);
            $Utilisateur=$this->getDoctrine()->getRepository(rendezvous::class)->find($id);
            $email=$Utilisateur->getEmail();
            $validrendezvous->setEmailR($email);


            $message = \Swift_Message::newInstance()
                ->setSubject('Accusé de réception')
                ->setFrom('nesrine.zouaoui1@esprit.tn')
                ->setTo($validrendezvous->getEmailR())
                ->setBody(
                    $this->renderView('@MyAppMail/Mail/rdv.html.twig',
                        array('message' => $validrendezvous->getMessage() )) ,'text/html');
            $this->get('mailer')->send($message);








            $this->getDoctrine()->getManager()->persist($validrendezvous);

            $this->getDoctrine()->getManager()->flush();
            return $this->redirectToRoute('EspaceReparateur');
        }
        return $this->render('@User/Default/validrendezvous.html.twig', array('form' => $Form->createView()));
    }
    public function listrendezvousAction()
    {
        $em = $this->getDoctrine()->getEntityManager();
        $currentUser = $this->getuser()->getEmail();

        $rendezvous = $em->getRepository('AppBundle:rendezvous')->findByEmail($currentUser);

        return $this->render('@User/Default/listrendezvous.html.twig', array('listrendezvous' => $rendezvous));


    }
    public function MaRendezVousValidAction()
    {

        $em = $this->getDoctrine()->getEntityManager();
        $currentUser = $this->getuser()->getEmail();

        $listrendezvous = $em->getRepository('AppBundle:validrendezvous')->findByEmailR( $currentUser);

        return $this->render('@User/Default/MaRendezVousValid.html.twig', array('listrendezvous' => $listrendezvous));

    }



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
        return $this->render('@User/Default/reclamation.html.twig',array('form'=>$form->createView()));
    }


    public function mesreclamationAction(){
        $em=$this->getDoctrine()->getManager();
        $user = $this->container->get('security.token_storage')->getToken()->getUser()->getId();

        $reclamation = $em->getRepository('AppBundle:reclamation')->findByUser($user);

        return $this->render('@User/Default/mesreclamation.html.twig',array('reclamation'=>$reclamation));
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
        return $this->render('@User/Default/Modifireclamation.html.twig',array('form' => $form->createView()));
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
        return $this->render('@User/Default/Modifirendezvous.html.twig',array('form' => $form->createView()));
    }
    public function refuserrendezvousAction($id, Request $request)
    {
        $currentUser=$this->getUser();
        $em = $this->getDoctrine()->getEntityManager();
        $rendezvous=$em->getRepository(rendezvous ::class)->find($id);


        $Utilisateur=$this->getDoctrine()->getRepository(rendezvous::class)->find($id);
        $email=$Utilisateur->getEmail();
        $rendezvous->setEmail($email);


        $message = \Swift_Message::newInstance()
            ->setSubject('Accusé de réception')
            ->setFrom('nesrine.zouaoui1@esprit.tn')
            ->setTo($rendezvous->getEmail())
            ->setBody(
                $this->renderView('@MyAppMail/Mail/rdv.html.twig',
                    array('message' => 'Malheureusement, je suis au regret de vous informer que je n’honorerai pas ce rendez-vous')));
        $this->get('mailer')->send($message);



        $this->getDoctrine()->getManager()->persist($rendezvous);

        $this->getDoctrine()->getManager()->flush();
        return $this->redirectToRoute('EspaceReparateur');


    }








    public function blogAction(Request $request )
    {
        $em = $this->getDoctrine()->getEntityManager();
        $actualites = $em->getRepository('AppBundle:Actualite')->findAll();




        return $this->render('@User/Default/blog.html.twig',array('actualite'=>$actualites ));
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
        $this->getDoctrine()->getManager()->persist($actualite);
        $this->getDoctrine()->getManager()->flush();
        return $this->render('@User/Default/blog.html.twig',array('actualite'=>$actualites));

    }








}
