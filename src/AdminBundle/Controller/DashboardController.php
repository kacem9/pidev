<?php

namespace AdminBundle\Controller;
use AppBundle\Entity\Actualite;
use AppBundle\Entity\produits;
use AppBundle\Entity\Velo;
use AppBundle\Repository\produitsRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
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
    public function AfficherUserAction()
    {




        return $this->render('@Admin/Default/user.html.twig');
    }
    public function AfficherveloAction()
    {
        $velo=$this->getDoctrine()
            ->getRepository(Velo::class)
            ->findAll();



        return $this->render('@Admin/Default/velo.html.twig', array('v'=>$velo));
    }
    public function AfficherproduitsAction()
    {
        $produit=$this->getDoctrine()
            ->getRepository(produits::class)
            ->findAll();

        return $this->render('@Admin/Default/produits.html.twig', array('p'=>$produit));



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

    function DeleteveloAction($id){
        $em=$this->getDoctrine()->getManager();
        $velo=$em->getRepository('AppBundle:Velo')->find($id);
        $em->remove($velo);
        $em->flush();
        return $this->redirectToRoute('admin_velo');

    }
    function DeleteproduitAction($id){
        $em=$this->getDoctrine()->getManager();
        $produits=$em->getRepository('AppBundle:produits')->find($id);
        if (!$produits) {
            throw $this->createNotFoundException('No user found for id '.$id);
        }
        $em->remove($produits);
        $em->flush();
        return $this->redirectToRoute('admin_ produits');

    }
    public function UpdateproduitAction(Request $request,$id)
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
            $produits ->setetatVendu(1);
            $em = $this->getDoctrine()->getEntityManager();
            $em->persist($produits);
            $em->flush();
            return $this->redirect($this->generateUrl('admin_ produits'));

        }
        return $this->render('@Admin/Default/Updateproduits.html.twig', array('form' => $form->createView()));

    }
    public function UpdateveloAction(Request $request,$id)
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

            $velo->setEtatLocation(1);
            $velo->setetatVendu(0);
            $velo->setUser($this->getUser());
            $em = $this->getDoctrine()->getEntityManager();
            $em->persist($velo);
            $em->flush();
            return $this->redirect($this->generateUrl('admin_velo'));


        }
        return $this->render('@Admin/Default/Update.html.twig', array('form' => $form->createView()));

    }

}