<?php

namespace EcomBundle\Controller;

use AppBundle\Entity\produits;
use AppBundle\Entity\User;
use AppBundle\Entity\Velo;


use Gregwar\CaptchaBundle\Type\CaptchaType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;

class VenteController extends Controller
{
    public function indexAction()
    {
        return $this->render('@Ecom/index.html.twig');
    }
    public function locationAction( Request $request)

    {


        $em = $this->getDoctrine()->getEntityManager();


        $user = $this->getuser();
        $velo= $em->getRepository('AppBundle:Velo')->findBy([
            'etatVendu'=>1,
                'etatLocation'=>0,

            ]



        );



        return $this->render('@Ecom/vente/Location.html.twig',array('velo'=>$velo))
            ;
    }

    //affichage produits
    public function produitsAction()

    {



        $em = $this->getDoctrine()->getEntityManager();

        $user = $this->getuser();


        $produits= $em->getRepository('AppBundle:Produits')->findBy([
                'etatVendu'=>0,
                'etatLocation'=>0,

            ]



        );




        return $this->render('@Ecom/vente/Produits.html.twig',array('produits'=>$produits));
    }

    public function formulaireAction(Request $request)
    {
        $velo = new Velo();
        $form = $this->createFormBuilder($velo)
            ->add('date_circulation', DateType::class, array('data' => new \DateTime(), 'widget' => 'single_text'))
            ->add('datePublication', DateType::class, array('data' => new \DateTime(), 'widget' => 'single_text'))
            ->add('prix')
            ->add('description')
            ->add('localitsation_velo')
            ->add('photo',FileType::class,array('data_class' => null))
            ->add('categories', EntityType::class, array(
                'class' => 'AppBundle:Categories',
                'choice_label' => 'nom',
                'mapped' => false
            ))
            ->add('age', EntityType::class, array(
                'class' => 'AppBundle:Categories',
                'choice_label' => 'age',
                'mapped' => false
            ))
            ->add('Entrecode', CaptchaType::class)
            ->getForm();

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $Photo = $velo->getPhoto();
            if ($Photo) {
                $fileName = md5(uniqid()) . '.' . $Photo->guessExtension();

                try {
                    $Photo->move(
                        $this->getParameter('Velo_directory'),
                        $fileName
                    );
                } catch (FileException $e) {

                }
                $velo->setPhoto($fileName);
            }
            $velo->setdateCirculation($form['date_circulation']->getData());
            $velo->setdatePublication($form['datePublication']->getData());
            $velo->setprix($form['prix']->getData());
            $velo->setdescription($form['description']->getData());
            $velo->setLocalitsationVelo($form['localitsation_velo']->getData());
            $velo->setcategories($form['categories']->getData());
            $velo->setAgeRecommande($form['age']->getData());

            $velo->setEtatLocation(0);
            $velo->setetatVendu(1);
            $em = $this->getDoctrine()->getEntityManager();
            $em->persist($velo);
            $em->flush();
            return $this->redirect($this->generateUrl('ecom_vente'));


        }
        return $this->render('@Ecom/vente/ajout_velo.html.twig', array('form' => $form->createView()));

    }
    function DeleteAction($id){
        $em=$this->getDoctrine()->getManager();
        $velo=$em->getRepository(Velo::class)
            ->find($id);
        $em->remove($velo);
        $em->flush();
        return $this->redirectToRoute('ecom_vente');

    }
    public function UpdateAction(Request $request,$id)
    {
        $em = $this->getDoctrine()->getEntityManager();

        $velo = $em->getRepository('AppBundle:Velo')->find($id);

        $form = $this->createFormBuilder($velo)
            ->add('date_circulation', DateType::class, array('data' => new \DateTime(), 'widget' => 'single_text'))
            ->add('datePublication', DateType::class, array('data' => new \DateTime(), 'widget' => 'single_text'))
            ->add('prix')
            ->add('description')
            ->add('localitsation_velo')
            ->add('photo',FileType::class,array('data_class' => null))
            ->add('categories', EntityType::class, array(
                'class' => 'AppBundle:Categories',
                'choice_label' => 'nom',
                'mapped' => false
            ))
            ->add('age', EntityType::class, array(
                'class' => 'AppBundle:Categories',
                'choice_label' => 'age',
                'mapped' => false
            ))
            ->getForm();

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $Photo = $velo->getPhoto();
            if ($Photo) {
                $fileName = md5(uniqid()) . '.' . $Photo->guessExtension();

                try {
                    $Photo->move(
                        $this->getParameter('Velo_directory'),
                        $fileName
                    );
                } catch (FileException $e) {

                }
                $velo->setPhoto($fileName);
            }
            $velo->setdateCirculation($form['date_circulation']->getData());
            $velo->setdatePublication($form['datePublication']->getData());
            $velo->setprix($form['prix']->getData());
            $velo->setdescription($form['description']->getData());
            $velo->setLocalitsationVelo($form['localitsation_velo']->getData());
            $velo->setphoto($form['photo']->getData());
            $velo->setUser($this->getUser());
            $velo->setcategories($form['categories']->getData());
            $velo->setAgeRecommande($form['age']->getData());

            $velo->setEtatLocation(0);
            $velo->setetatVendu(1);
            $velo->setUser($this->getUser());
            $em = $this->getDoctrine()->getEntityManager();
            $em->persist($velo);
            $em->flush();
            return $this->redirect($this->generateUrl('ecom_vente'));


        }
        return $this->render('@Ecom/vente/Update.html.twig', array('form' => $form->createView()));

    }
    //add produits
    public function ProduitsaddAction(Request $request)
    {
        $produits = new produits();
        $form = $this->createFormBuilder($produits)
            ->add('nom')
            ->add('modele')
            ->add('type',ChoiceType::class, array('choices'=>array
            ('Pompe' => 'Pompe',
                'Rustines avec colle' => 'Rustines avec colle',
                'Démontes-pneus' => 'Démontes-pneus',
                'Pneus de rechange' => 'Pneus de rechange',
                'Clé à pédales' => 'Clé à pédales',
                'Clé à rayons' => 'Clé à rayons',
                'Rayons de rechange' => 'Rayons de rechange ',)
    ))
            ->add('prix')
            ->add('photo',FileType::class,array('data_class' => null))
            ->add('Entrecode', CaptchaType::class)
            ->getForm();

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $Photo = $produits->getPhoto();
            if ($Photo) {
                $fileName = md5(uniqid()) . '.' . $Photo->guessExtension();

                try {
                    $Photo->move(
                        $this->getParameter('Velo_directory'),
                        $fileName
                    );
                } catch (FileException $e) {

                }
                $produits->setPhoto($fileName);
            }
            $produits->setNom($form['nom']->getData());
            $produits->setModele($form['modele']->getData());
            $produits->setType($form['type']->getData());
            $produits->setprix($form['prix']->getData());

            $produits->setEtatLocation(0);
            $produits ->setetatVendu(0);
            $em = $this->getDoctrine()->getEntityManager();
            $em->persist($produits);
            $em->flush();
            return $this->redirect($this->generateUrl('ecom_produits'));


        }
        return $this->render('@Ecom/vente/Addproduits.html.twig', array('form' => $form->createView()));

    }
    //delete produits

    function DeletepAction($id){
        $em=$this->getDoctrine()->getManager();
        $produits=$em->getRepository('AppBundle:produits')->find($id);
        if (!$produits) {
            throw $this->createNotFoundException('No user found for id '.$id);
        }
        $em->remove($produits);
        $em->flush();
        return $this->redirectToRoute('ecom_produits');

    }
    //update produits

    public function UpdatepAction(Request $request,$id)
    {
        $em = $this->getDoctrine()->getEntityManager();

        $produits = $em->getRepository('AppBundle:produits')->find($id);

        $form = $this->createFormBuilder($produits)
            ->add('nom')
            ->add('modele')
            ->add('prix')
            ->add('type',ChoiceType::class, array('choices'=>array
            ('Pompe' => 'Pompe',
                'Rustines avec colle' => 'Rustines avec colle',
                'Démontes-pneu' => 'Démontes-pneu',
                'Pneu de rechange' => 'Pneu de rechange',
                'Clé à pédales' => 'Clé à pédales',
                'Clé à rayons' => 'Clé à rayons',
                'Rayons de rechange' => 'Rayons de rechange ',)
            ))

            ->add('photo',FileType::class,array('data_class' => null))
            ->getForm();

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $Photo = $produits->getPhoto();
            if ($Photo) {
                $fileName = md5(uniqid()) . '.' . $Photo->guessExtension();

                try {
                    $Photo->move(
                        $this->getParameter('Velo_directory'),
                        $fileName
                    );
                } catch (FileException $e) {

                }
                $produits->setPhoto($fileName);
            }


            $produits->setNom($form['nom']->getData());
            $produits->setModele($form['modele']->getData());

            $produits->setprix($form['prix']->getData());
            $produits->setType($form['type']->getData());
            $produits->setEtatLocation(0);
            $produits ->setetatVendu(0);
            $em = $this->getDoctrine()->getEntityManager();
            $em->persist($produits);
            $em->flush();
            return $this->redirect($this->generateUrl('ecom_produits'));

        }
        return $this->render('@Ecom/vente/Updateproduits.html.twig', array('form' => $form->createView()));

    }
    /*function RechercheAction(Request $request){
        $velo=new Velo();
        $Form=$this->createFormBuilder($velo)
            ->add('categories', TextType::class)
            ->add('Recherche',SubmitType::class)
            ->getForm();
        $Form->handleRequest($request);
        if($Form->isSubmitted()){

            $velo=$this->getDoctrine()->getRepository(Velo::class)->findBy(array('categories'=>$velo->getCategories()));
        }
        return $this->render('@Ecom/vente/Recherche.html.twig',
            array('velo'=>$velo, 'f' => $Form->createView()));

    }*/

}