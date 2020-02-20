<?php

namespace EcomBundle\Controller;

use AppBundle\Entity\User;
use AppBundle\Entity\Velo;
use AppBundle\Entity\Actualite;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\HttpFoundation\Request;

class DefaultController extends Controller
{
    public function actualiteAction()
    {

        $em = $this->getDoctrine()->getEntityManager();
        $actualites = $em->getRepository('AppBundle:Actualite')->findAll();

        return $this->render('@Ecom/index.html.twig',array('actualites'=>$actualites));
    }
    public function locationAction()
    {
        $em = $this->getDoctrine()->getEntityManager();



        $velo= $em->getRepository('AppBundle:Velo')->findAll();


        return $this->render('@Ecom/Ecom/Location.html.twig',array('velo'=>$velo));
    }
    public function updateformulaireAction(Request $request,$id)
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
                'choice_label' => 'Nom',
                'mapped' => false
            ))
            ->add('age_recommande', EntityType::class, array(
                'class' => 'AppBundle:Categories',
                'choice_label' => 'Age',
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
          //$velo->setphoto($form['photo']->getData());
            $velo->setUser($this->getUser());
            $velo->setcategories($form['categories']->getData());
            $velo->setAgeRecommande($form['age_recommande']->getData());

            $velo->setEtatLocation(1);
            $velo->setetatVendu(0);

            $velo->setRating(0);
            $velo->setNbruser(0);

            $em = $this->getDoctrine()->getEntityManager();
            $em->persist($velo);
            $em->flush();
            return $this->redirect($this->generateUrl('ecom_location'));


        }
        return $this->render('@Ecom/Ecom/Updateformulaire.html.twig', array('form' => $form->createView()));

    }
    public function formulaireAction(Request $request)
    {
        $velo = new Velo();
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
            ->add('age_recommande', EntityType::class, array(
                'class' => 'AppBundle:Categories',
                'choice_label' => 'Age',
                'mapped' => false
            ))
            ->getForm();

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $Photo = $velo->getPhoto();
            if ($Photo) {
                $fileName = md5(uniqid()) . '.' . $Photo->guessExtension();


                    $Photo->move(
                        $this->getParameter('Velo_directory'),
                        $fileName
                    );

                $velo->setPhoto($fileName);
            }
            $velo->setdateCirculation($form['date_circulation']->getData());
            $velo->setdatePublication($form['datePublication']->getData());
            $velo->setPrixLocation($form['prix_location']->getData());
            $velo->setDescription($form['description']->getData());
            $velo->setQuantite($form['quantite']->getData());
            $velo->setLocalitsationVelo($form['localitsation_velo']->getData());
            $velo->setUser($this->getUser());
            $velo->setcategories($form['categories']->getData());
            $velo->setAgeRecommande($form['age_recommande']->getData());
            $velo->setEtatLocation(1);
            $velo->setetatVendu(0);
            $velo->setRating(0);
            $velo->setNbruser(0);
            $em = $this->getDoctrine()->getEntityManager();
            $em->persist($velo);
            $em->flush();
            return $this->redirect($this->generateUrl('ecom_location'));


        }
        return $this->render('@Ecom/Ecom/formulaire.html.twig', array('form' => $form->createView()));

    }

    function DeletelocationAction($id){
        $em=$this->getDoctrine()->getManager();
        $velo=$em->getRepository(Velo::class)
            ->find($id);
        $em->remove($velo);
        $em->flush();
        return $this->redirectToRoute('ecom_location');

    }


}