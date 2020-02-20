<?php

namespace AdminBundle\Controller;

use AppBundle\Entity\Categories;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;

class DefaultController extends Controller
{
    public function indexAction()
    {
        return $this->render('AdminBundle:Default:index.html.twig');
    }

    public function liste_categoriesAction()
    {
        $em = $this->getDoctrine()->getEntityManager();
        $categories = $em->getRepository('AppBundle:Categories')->findAll();


            return $this->render('@Admin/Categories/AfficherCategories.html.twig',array('categories'=>$categories));
    }


    public function ajouterCategorieAction(Request $request)
    {
        $categorie = new Categories();
        $form = $this->createFormBuilder($categorie)
            ->add('Nom', TextType::class)
            ->add('Age', TextType::class)
            ->getForm();

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $categorie->setNom($form['Nom']->getData());
            $categorie->setAge($form['Age']->getData());


            $em = $this->getDoctrine()->getEntityManager();
            $em->persist($categorie);
            $em->flush();

            $request->getSession()->getFlashBag()->add('success', 'Categorie ajoutée avec succés');
            return $this->redirect($this->generateUrl('admin_listeCategories'));

        }
        return $this->render('@Admin/Categories/AjouterCategorie.html.twig', array('form' => $form->createView()));
    }
    public function UpdateCategorieAction(Request $request,$id)
    {
        $em = $this->getDoctrine()->getEntityManager();

        $categorie= $em->getRepository('AppBundle:Categories')->find($id);


        $form = $this->createFormBuilder($categorie)
            ->add('Nom', TextType::class)
            ->add('Age', TextType::class)
            ->getForm();

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $categorie->setNom($form['Nom']->getData());
            $categorie->setAge($form['Age']->getData());


            $em = $this->getDoctrine()->getEntityManager();
            $em->persist($categorie);
            $em->flush();

            $request->getSession()->getFlashBag()->add('success', 'Categorie ajoutée avec succés');
            return $this->redirect($this->generateUrl('admin_listeCategories'));

        }
        return $this->render('@Admin/Categories/ModifierCategorie.html.twig', array('form' => $form->createView()));
    }
    public function SupprimerCategorieAction(Request $request,$id)
    {

        $em = $this->getDoctrine()->getEntityManager();
        $Categorie = $em->getRepository('AppBundle:Categories')->find($id);
        if (!$Categorie) {
            throw $this->createNotFoundException('No actualite found for id '.$id);
        }
        $em->remove($Categorie);
        $em->flush();
        $request->getSession()->getFlashBag()->add('success', ' Categorie supprimée avec succés');
        return $this->redirect($this->generateUrl('admin_listeCategories'));
    }
    public function ListeVelolouerAction()

    {

        $em = $this->getDoctrine()->getEntityManager();


        $velo = $em->getRepository('AppBundle:Velo')->findBy([
            'etatVendu' => 0,
            'etatLocation' => 1,

        ]);


        return $this->render('@Admin/ListeVelo/ListeVeloLouer.html.twig', array('velo' => $velo));
    }

    public function UpdateVeloLouerAction(Request $request,$id)
    {
        $em = $this->getDoctrine()->getEntityManager();

        $velo = $em->getRepository('AppBundle:Velo')->find($id);

        $form = $this->createFormBuilder($velo)
            ->add('date_circulation', DateType::class, array('data' => new \DateTime(), 'widget' => 'single_text'))
            ->add('datePublication', DateType::class, array('data' => new \DateTime(), 'widget' => 'single_text'))
            ->add('prix_location')
            ->add('description')
            ->add('quantite',IntegerType::class)
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
            $velo->setPrixLocation($form['prix_location']->getData());
            $velo->setdescription($form['description']->getData());
            $velo->setQuantite($form['quantite']->getData());

            $velo->setLocalitsationVelo($form['localitsation_velo']->getData());
            $velo->setphoto($form['photo']->getData());
            $velo->setUser($this->getUser());
            $velo->setcategories($form['categories']->getData());
            $velo->setAgeRecommande($form['age']->getData());

            $velo->setEtatLocation(1);
            $velo->setetatVendu(0);
            $velo->setUser($this->getUser());
            $velo->setRating(0);
            $velo->setNbruser(0);

            $em = $this->getDoctrine()->getEntityManager();
            $em->persist($velo);
            $em->flush();
            return $this->redirect($this->generateUrl('ListeVelolouer'));


        }
        return $this->render('@Admin/ListeVelo/ModifierVeloLouer.html.twig', array('form' => $form->createView()));

    }
    public function SupprimerveloLocationAction(Request $request,$id)
    {

        $em = $this->getDoctrine()->getEntityManager();
        $velo = $em->getRepository('AppBundle:Velo')->find($id);
        if (!$velo) {
            throw $this->createNotFoundException('No velo found for id '.$id);
        }
        $em->remove($velo);
        $em->flush();
        $request->getSession()->getFlashBag()->add('success', ' velo supprimée avec succés');
        return $this->redirect($this->generateUrl('ListeVelolouer'));
    }
}
