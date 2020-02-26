<?php

namespace UserBundle\Controller;

use AppBundle\Entity\User;
use Symfony\component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CountryType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Gregwar\CaptchaBundle\GregwarCaptchaBundle;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Gregwar\CaptchaBundle\Type\CaptchaType;

use AppBundle\Entity\produits;

use AppBundle\Entity\Velo;

class DefaultController extends Controller
{
    public function indexAction()
    {
        $em = $this->getDoctrine()->getEntityManager();


        $user = $this->getuser();
        $velo= $em->getRepository('AppBundle:Velo')->findBy([
                'etatVendu'=>1,
                'etatLocation'=>0,

            ]

        );
        $produits= $em->getRepository('AppBundle:Produit')->findAll();
        $velos = $em->getRepository('AppBundle:Velo')->AfficherVelo();

        $event= $em->getRepository('AppBundle:Event')->findBy([
                'etat'=>1,


            ]

        );




        return $this->render('@User/Default/index.html.twig',array('velo'=>$velo,'produits'=>$produits,
            'velos'=>$velos,'event'=>$event));
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
            return $this->redirect($this->generateUrl('ecom_homepage'));


        }
        return $this->render('@User/Default/inscription.html.twig', array('form' => $form->createView()));
    }
    public function ReparateursAction()
    {

        $em = $this->getDoctrine()->getEntityManager();

        $expr =$em->createQueryBuilder()->expr();
        $query =$em->getRepository('AppBundle:User')->createQueryBuilder('u')
            ->where('u.roles LIKE :bo')
            ->setParameters([
                    'bo' => '%"ROLE_REPARATEUR"%']
            )

            ->getQuery();

        $reparateurs = $query->getResult();


        return $this->render('@User/Default/Reparateurs.html.twig', array('Reparateurs' => $reparateurs));


    }
    //affichage sur l'acceuil(accessoires)
    public function HomeaccessoiresAction( Request $request)

    {


        $em = $this->getDoctrine()->getEntityManager();


        $user = $this->getuser();
        $produits= $em->getRepository('AppBundle:Produit')->findAll();







        return $this->render('@User/Default/Homeaccessoires.html.twig',array('produits'=>$produits))
            ;
    }
    //affichage velo dans l'acceuil
    public function HomeveloAction( Request $request)

    {


        $em = $this->getDoctrine()->getEntityManager();


        $user = $this->getuser();
        $velo= $em->getRepository('AppBundle:Velo')->findBy([
                'etatVendu'=>1,
                'etatLocation'=>0,

            ]



        );



        return $this->render('@User/Default/Homevelo.html.twig',array('velo'=>$velo));
    }
    public function RoadBikeAAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $repository = $em->getRepository('AppBundle:Velo');
        $query = $em->createQueryBuilder()
            ->select('v')->from('AppBundle:Velo', 'v')
            ->where('v.categories = :categories')
            ->andwhere('v.etatVendu = 1')
            ->andwhere('v.etatLocation = 0')
            ->setParameter('categories', '5')
            ->getQuery();

        $velos = $query->getResult();


        return $this->render('@User/Default/RoadBikeacceuil.twig', array('velo' => $velos));
    }

    public function KidsBikesAAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $repository = $em->getRepository('AppBundle:Velo');
        $query = $em->createQueryBuilder()
            ->select('v')->from('AppBundle:Velo', 'v')
            ->where('v.categories = :categories')
            ->andwhere('v.etatVendu = 1')
            ->andwhere('v.etatLocation = 0')
            ->setParameter('categories', '2')
            ->getQuery();

        $velos = $query->getResult();


        return $this->render('@User/Default/KidsBikesacceuil.twig', array('velo' => $velos));
    }

    public function MountainBikesAAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $repository = $em->getRepository('AppBundle:Velo');
        $query = $em->createQueryBuilder()
            ->select('v')->from('AppBundle:Velo', 'v')
            ->where('v.categories = :categories')
            ->andwhere('v.etatVendu = 1')
            ->andwhere('v.etatLocation = 0')
            ->setParameter('categories', '1')
            ->getQuery();

        $velos = $query->getResult();


        return $this->render('@User/Default/MountainBikesacceuil.html.twig', array('velo' => $velos));
    }

    public function SportsBikeAAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $repository = $em->getRepository('AppBundle:Velo');
        $query = $em->createQueryBuilder()
            ->select('v')->from('AppBundle:Velo', 'v')
            ->where('v.categories = :categories')
            ->andwhere('v.etatVendu = 1')
            ->andwhere('v.etatLocation = 0')
            ->setParameter('categories', '3')
            ->getQuery();

        $velos = $query->getResult();


        return $this->render('@User/Default/SportsBikeacceuil.html.twig', array('velo' => $velos));
    }

    public function CyclocrossBikeAAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $repository = $em->getRepository('AppBundle:Velo');
        $query = $em->createQueryBuilder()
            ->select('v')->from('AppBundle:Velo', 'v')
            ->where('v.categories = :categories')
            ->andwhere('v.etatVendu = 1')
            ->andwhere('v.etatLocation = 0')
            ->setParameter('categories', '4')
            ->getQuery();

        $velos = $query->getResult();


        return $this->render('@User/Default/CyclocrossBikeacceuil.html.twig', array('velo' => $velos));
    }





public function AfficherveloAction($id)
    {
        $em = $this->getDoctrine()->getEntityManager();
        $velo = $em->getRepository('AppBundle:Velo')->findById($id);
        return $this->render('@User/Default/Affichervelo.html.twig', array('velo' => $velo));
    }


}
