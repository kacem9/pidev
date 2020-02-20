<?php

namespace AdminBundle\Controller;
use AppBundle\Entity\Actualite;
use AppBundle\Entity\Event;
use AppBundle\Entity\User;
use Gregwar\CaptchaBundle\Type\CaptchaType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CountryType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Constraints\DateTime;

class DashboardController extends Controller
{
    public function indexAction()
    {
        return $this->render('@Admin/accueil.html.twig');
    }
    public function  ajouteractualiteAction(Request $request )
    {
        $actualite = new Actualite();
        $form = $this->createFormBuilder($actualite)

            ->add('titre',TextType::class)
            ->add('image',FileType::class,array('data_class' => null,'required' => true))
            ->add('description',TextAreaType::class)
            ->getForm();

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid())
        {
            $Image = $actualite->getImage();
            if($Image){
                $fileName = md5(uniqid()).'.'.$Image->guessExtension();

                try {
                    $Image->move(
                        $this->getParameter('Actualite_directory'),
                        $fileName
                    );
                } catch (FileException $e) {

                }
                $actualite->setImage($fileName);
            }

            $actualite->setDatePublication(new \DateTime());



            $em = $this->getDoctrine()->getEntityManager();
            $em->persist($actualite);
            $em->flush();

            $request->getSession()->getFlashBag()->add('success','Actualité ajoutée avec succés');

        }
        return $this->render('@Admin/Default/Ajouter.html.twig',array('form' => $form->createView()));
    }
    public function AfficherActualiteBackAction()
    {
        $actualite=$this->getDoctrine()->getRepository(Actualite::class)->findAll();
        return $this->render('@Admin/Default/AfficherActualite.html.twig',array('actualite'=>$actualite));
    }
    public function SupprimerActualiteBackAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $actualite = $em->getRepository(Actualite ::class)->find($id);
        $em->remove($actualite);
        $em->flush();
        return $this->redirectToRoute('admin_afficheractualite');
    }
    public function ModifierActualiteBackAction($id,Request $request)
    {
        $em=$this->getDoctrine()->getManager();
        $actualite=$em->getRepository(Actualite ::class)->find($id);
        $form = $this->createFormBuilder($actualite)
            ->add('titre',TextType::class)
            ->add('image',FileType::class,array('data_class' => null,'required' => true))
            ->add('description',TextAreaType::class)
            ->getForm();
        $form->handleRequest($request);
        if ($form->isSubmitted()){
            $em=$this->getDoctrine()->getManager();
            $em->flush();
            return $this->redirectToRoute('admin_afficheractualite');
        }
        return $this->render('@Admin/Default/ModifierActualiteBack.html.twig',array('form'=>$form->createView()));
    }
    public function detailsActualiteBackAction($id)
    {
        $em = $this->getDoctrine()->getEntityManager();
        $actualite = $em->getRepository('AppBundle:Actualite')->find($id);

        return $this->render('AdminBundle:Actualite:DetailsActualiteBack.html.twig',array('actualite'=>$actualite));
    }

    public function AfficherUserBackAction()
    {
        $user=$this->getDoctrine()->getRepository(User::class)->findAll();
        return $this->render('@Admin/User/user.html.twig',array('user'=>$user));
    }

    public function AjouterUserBackAction(Request $request)
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
            return $this->redirect($this->generateUrl('admin_AfficherUser'));


        }
        return $this->render('@Admin/User/AjouterUserBack.html.twig', array('form' => $form->createView()));
    }
    public function SupprimerUserBackAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $user = $em->getRepository(User ::class)->find($id);
        $em->remove($user);
        $em->flush();
        return $this->redirectToRoute('admin_AfficherUser');
    }

    public function ModifierUserBackAction($id,Request $request)
    {
        $em=$this->getDoctrine()->getManager();
        $user=$em->getRepository(User ::class)->find($id);
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
        if ($form->isSubmitted()){
            $em=$this->getDoctrine()->getManager();
            $em->flush();
            return $this->redirectToRoute('admin_AfficherUser');
        }
        return $this->render('@Admin/User/ModifierUserBack.html.twig',array('form'=>$form->createView()));
    }

    public function detailsUserBackAction($id)
    {
        $em = $this->getDoctrine()->getEntityManager();
        $user = $em->getRepository('AppBundle:User')->find($id);

        return $this->render('AdminBundle:User:DetailsUserBack.html.twig',array('user'=>$user));
    }
    public function AfficherEventBackAction()
    {
        $event=$this->getDoctrine()->getRepository(Event::class)->findAll();
        return $this->render('@Admin/Event/Event.html.twig',array('event'=>$event));
    }
    public function AjouterEventBackAction(Request $request)
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
            return $this->redirectToRoute('admin_afficher_event');
        }
        return $this->render('@Admin/event/AjouterEventBack.html.twig', array(
            "form"=>$form->createView()

        ));
    }
    public function SupprimerEventBackAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $event = $em->getRepository(Event ::class)->find($id);
        $em->remove($event);
        $em->flush();
        return $this->redirectToRoute('admin_afficher_event');
    }
    public function ModifierEventBackAction($id,Request $request)
    {
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
            return $this->redirectToRoute('admin_afficher_event');
        }
        return $this->render('@Admin/event/ModifierEventBack.html.twig',array('form'=>$form->createView()));
    }
    public function detailsEventBackAction($id)
    {
        $em = $this->getDoctrine()->getEntityManager();
        $event = $em->getRepository('AppBundle:Event')->find($id);

        return $this->render('AdminBundle:Event:DetailsEventBack.html.twig',array('event'=>$event));
    }
    public function ValiderEventAction($id)
    {
        $em=$this->getDoctrine()->getManager();
        $val=$em->getRepository('AppBundle:Event')->find($id);
        $val->setEtat(1);
        $em->flush();
        return $this->redirectToRoute('admin_afficher_event');
    }
}