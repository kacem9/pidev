<?php

namespace EcomBundle\Controller;

use AppBundle\Entity\Produit;
use AppBundle\Entity\User;
use AppBundle\Entity\Velo;


use Gregwar\CaptchaBundle\Type\CaptchaType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
class VenteController extends Controller
{
    public function indexAction(SessionInterface $session)
    {

        return $this->render('@Ecom/index.html.twig');
    }
    public  function NewmapAction(){
        return $this->render('@Ecom/vente/Newmap.html.twig');
    }
    public function afficherveloAction( Request $request)

    {


        $em = $this->getDoctrine()->getEntityManager();


        $user = $this->getuser();
        $velo= $em->getRepository('AppBundle:Velo')->findBy([
                'etatVendu'=>1,
                'etatLocation'=>0,

            ]



        );



        return $this->render('@Ecom/vente/affichervelo.html.twig',array('velo'=>$velo))
            ;
    }

    //affichage produits
    public function produitsAction()

    {



        $em = $this->getDoctrine()->getEntityManager();

$user=$this->getUser();

        $produits= $em->getRepository('AppBundle:Produit')->findBy([
            'user'=>$user,



        ]);



        return $this->render('@Ecom/vente/Produits.html.twig',array('produits'=>$produits));
    }

    public function formulaireVeloAction(Request $request)
    {
        $velo = new Velo();
        $form = $this->createFormBuilder($velo)
            ->add('date_circulation', DateType::class, array('data' => new \DateTime(), 'widget' => 'single_text'))
            ->add('datePublication', DateType::class, array('data' => new \DateTime(), 'widget' => 'single_text'))
            ->add('price')
            ->add('description')
            ->add('quantity',IntegerType::class)
            ->add('localitsation_velo')
            ->add('photo',FileType::class,array('data_class' => null))
            ->add('categories')
            ->add('Entrecode', CaptchaType::class)

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
            $velo->setPrice($form['price']->getData());
            $velo->setDescription($form['description']->getData());
            $velo->setQuantity($form['quantity']->getData());
            $velo->setLocalitsationVelo($form['localitsation_velo']->getData());
            $velo->setUser($this->getUser());
            $velo->setcategories($form['categories']->getData());

            $velo->setEtatLocation(0);
            $velo->setetatVendu(1);

            $em = $this->getDoctrine()->getEntityManager();
            $em->persist($velo);
            $em->flush();
            return $this->redirect($this->generateUrl('ecom_affichervelo'));


        }
        return $this->render('@Ecom/vente/ajout_velo.html.twig', array('form' => $form->createView()));

    }
    function DeleteAction($id){
        $em=$this->getDoctrine()->getManager();
        $velo=$em->getRepository(Velo::class)
            ->find($id);
        if (!$velo) {
            throw $this->createNotFoundException('No user found for id '.$id);
        }
        $em->remove($velo);
        $em->flush();
        return $this->redirectToRoute('ecom_affichervelo');

    }
    public function UpdateAction(Request $request,$id)
    {
        $em = $this->getDoctrine()->getEntityManager();

        $velo = $em->getRepository('AppBundle:Velo')->find($id);

        $form = $this->createFormBuilder($velo)
            ->add('date_circulation', DateType::class, array('data' => new \DateTime(), 'widget' => 'single_text'))
            ->add('datePublication', DateType::class, array('data' => new \DateTime(), 'widget' => 'single_text'))
            ->add('price')
            ->add('description')
            ->add('quantity',IntegerType::class)
            ->add('localitsation_velo')
            ->add('photo',FileType::class,array('data_class' => null))
            ->add('categories')
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
            $velo->setPrice($form['price']->getData());
            $velo->setdescription($form['description']->getData());
            $velo->setQuantity($form['quantity']->getData());
            $velo->setLocalitsationVelo($form['localitsation_velo']->getData());
            //$velo->setphoto($form['photo']->getData());
            $velo->setUser($this->getUser());
            $velo->setcategories($form['categories']->getData());


            $velo->setEtatLocation(0);
            $velo->setetatVendu(1);
            ;

            $em = $this->getDoctrine()->getEntityManager();
            $em->persist($velo);
            $em->flush();
            return $this->redirect($this->generateUrl('ecom_affichervelo'));


        }
        return $this->render('@Ecom/vente/Update.html.twig', array('form' => $form->createView()));

    }
    //add produits
    public function ProduitsaddAction(Request $request)
    {
        $produits = new Produit();
        $form = $this->createFormBuilder($produits)
            ->add('model')
            ->add('type',ChoiceType::class, array('choices'=>array
            ('Pompe' => 'Pompe',
                'Rustines avec colle' => 'Rustines avec colle',
                'Démontes-pneus' => 'Démontes-pneus',
                'Pneus de rechange' => 'Pneus de rechange',
                'Clé à pédales' => 'Clé à pédales',
                'Clé à rayons' => 'Clé à rayons',
                'Rayons de rechange' => 'Rayons de rechange ',)
            ))
            ->add('price')
            ->add('quantity',IntegerType::class)
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
            $produits->setModel($form['model']->getData());
            $produits->setType($form['type']->getData());
            $produits->setprice($form['price']->getData());
            $produits->setQuantity($form['quantity']->getData());
            $produits->setUser($this->getUser());



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
        $produits=$em->getRepository('AppBundle:Produit')->find($id);
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

        $produits = $em->getRepository('AppBundle:Produit')->find($id);

        $form = $this->createFormBuilder($produits)
            ->add('model')
            ->add('price')
            ->add('quantity')

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


            $produits->setModel($form['model']->getData());

            $produits->setprice($form['price']->getData());
            $produits->setQuantity($form['quantity']->getData());

            $produits->setType($form['type']->getData());

            $em = $this->getDoctrine()->getEntityManager();
            $em->persist($produits);
            $em->flush();
            return $this->redirect($this->generateUrl('ecom_produits'));

        }
        return $this->render('@Ecom/vente/Updateproduits.html.twig', array('form' => $form->createView()));

    }
    public function RoadBikeAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $repository = $em->getRepository('AppBundle:Velo');
        $query = $em->createQueryBuilder()
            ->select('v')->from('AppBundle:Velo', 'v')
            ->where('v.categories = :categories')
            ->setParameter('categories', '5')
            ->getQuery();

        $velos = $query->getResult();


        return $this->render('@Ecom/vente/affichervelo.html.twig', array('velo' => $velos));    }

    public function KidsBikesAction(Request $request)
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


        return $this->render('@Ecom/vente/affichervelo.html.twig', array('velo' => $velos));
    }

    public function MountainBikesAction(Request $request)
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


        return $this->render('@Ecom/vente/affichervelo.html.twig', array('velo' => $velos));
    }

    public function SportsBikeAction(Request $request)
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


        return $this->render('@Ecom/Vente/Location.html.twig', array('velo' => $velos));
    }

    public function CyclocrossBikeAction(Request $request)
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


        return $this->render('@Ecom/vente/affichervelo.html.twig', array('velo' => $velos));
    }

    public function DateascAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $repository = $em->getRepository('AppBundle:Velo');
        $query = $em->createQueryBuilder()
            ->select('v')->from('AppBundle:Velo', 'v')
            ->andwhere('v.etatVendu = 1')
            ->andwhere('v.etatLocation = 0')
            ->orderBy('v.datePublication', 'ASC')
            ->getQuery();

        $velos = $query->getResult();
        return $this->render('@Ecom/vente/affichervelo.html.twig', array('velo' => $velos));
    }
    public function DatedescendantAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $repository = $em->getRepository('AppBundle:Velo');
        $query = $em->createQueryBuilder()
            ->select('v')->from('AppBundle:Velo', 'v')
            ->andwhere('v.etatVendu = 1')
            ->andwhere('v.etatLocation = 0')
            ->orderBy('v.datePublication', 'DESC')
            ->getQuery();

        $velos = $query->getResult();
        return $this->render('@Ecom/vente/affichervelo.html.twig', array('velo' => $velos));    }
    public function PrixproduitsascAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $repository = $em->getRepository('AppBundle:produits');
        $query = $em->createQueryBuilder()
            ->select('p')->from('AppBundle:Produit', 'p')
            ->andwhere('v.etatVendu = 1')
            ->andwhere('v.etatLocation = 0')
            ->orderBy('p.prix', 'ASC')
            ->getQuery();

        $produits = $query->getResult();
        return $this->render('@Ecom/vente/Produits.html.twig', array('produits' => $produits));
    }
    public function PrixdescendantproduitAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $repository = $em->getRepository('AppBundle:produits');
        $query = $em->createQueryBuilder()
            ->select('p')->from('AppBundle:Produit', 'p')
            ->andwhere('v.etatVendu = 1')
            ->andwhere('v.etatLocation = 0')
            ->orderBy('p.prix', 'DESC')
            ->getQuery();

        $produits = $query->getResult();
        return $this->render('@Ecom/vente/Produits.html.twig', array('produits' => $produits));
    }
    //trie par catégorie
    public function TriecAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $repository = $em->getRepository('AppBundle:Velo');
        $query = $em->createQueryBuilder()
            ->select('v')->from('AppBundle:Velo', 'v')
            ->andwhere('v.etatVendu = 1')
            ->andwhere('v.etatLocation = 0')
            ->orderBy('v.categories')
            ->getQuery();

        $velos = $query->getResult();
        return $this->render('@Ecom/vente/affichervelo.html.twig', array('velo' => $velos));
    }
    public function searchAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $requestString = $request->get('q');
        $produits =  $em->getRepository('AppBundle:Produit')->findEntitiesByString($requestString);
        if(!$produits) {
            $result['produits']['error'] = "produit Not found :( ";
        } else {
            $result['produits'] = $this->getRealEntities($produits);
        }
        return new Response(json_encode($result));
    }


    public function getRealEntities($produits){
        foreach ($produits as $produit){
            $realEntities[$produit->getId()] = [$produit->getNom()];

        }
        return $realEntities;
    }






}