<?php

namespace EcomBundle\Controller;

use AppBundle\Entity\Comment;
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
        $Comment = $em->getRepository('AppBundle:Comment')->findAll();
        return $this->render('@Ecom/index.html.twig',array('actualites'=>$actualites,'Comment'=>$Comment));
    }
    public function CommentActVAction(Request $request )

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
            return $this->redirectToRoute('ecom_homepage');
        }
        return $this->render('@Ecom/Ecom/Comment.html.twig', array(
            "form"=>$form->createView()

        ));
    }

    public function ajouterUneEtoileVAction($id,$nombre,Request $request){
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
        return $this->render('@Ecom/index.html.twig',array('actualites'=>$actualites ,'Comment'=>$Comment));

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
            ->add('price_location')
            ->add('description')
            ->add('quantity',IntegerType::class)
            ->add('localitsation_velo')
            ->add('photo',FileType::class,array('data_class' => null))
            ->add('categories')


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
            $velo->setPriceLocation($form['price_location']->getData());
            $velo->setdescription($form['description']->getData());
            $velo->setQuantity($form['quantity']->getData());
            $velo->setLocalitsationVelo($form['localitsation_velo']->getData());
          //$velo->setphoto($form['photo']->getData());
            $velo->setUser($this->getUser());
            $velo->setcategories($form['categories']->getData());


            $velo->setEtatLocation(1);
            $velo->setetatVendu(0);
;

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
            ->add('price_location')
            ->add('description')
            ->add('quantity',IntegerType::class)
            ->add('localitsation_velo')
            ->add('photo',FileType::class,array('data_class' => null))
            ->add('categories')

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
            $velo->setPriceLocation($form['price_location']->getData());
            $velo->setDescription($form['description']->getData());
            $velo->setQuantity($form['quantity']->getData());
            $velo->setLocalitsationVelo($form['localitsation_velo']->getData());
            $velo->setUser($this->getUser());
            $velo->setcategories($form['categories']->getData());

            $velo->setEtatLocation(1);
            $velo->setetatVendu(0);

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