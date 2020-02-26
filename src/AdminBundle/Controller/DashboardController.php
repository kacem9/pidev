<?php

namespace AdminBundle\Controller;
use AppBundle\Entity\Actualite;
use AppBundle\Entity\Event;
use AppBundle\Entity\Produit;
use AppBundle\Entity\reclamation;

use AppBundle\Entity\User;
use AppBundle\Entity\Velo;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;

use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
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
        $em = $this->getDoctrine()->getEntityManager();




        $nbreveloLouer = $this->formatChart($em->getRepository('AppBundle:Velo')->countByMonthVeloLouer());
        $nbrevelpasLouer = $this->formatChart($em->getRepository('AppBundle:Velo')->countByMonthVelopasLouer());
       $countByMonthCommande= $this->formatChart($em->getRepository('AppBundle:Commande')->countByMonthCommande());

        return $this->render('@Admin/accueil.html.twig',[

            'nbreveloLouer' =>  $nbreveloLouer,
            'nbrevelpasLouer' =>  $nbrevelpasLouer,
            'countByMonthCommande' =>  $countByMonthCommande,

        ]);





    }
    public function formatChart($data){

        $months =  array_fill_keys(range(1, 12), 0);


        foreach($data as $item){
            $months[$item['month']] = $item['total'];

        }
        return json_encode(array_values($months));
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







    public function modifieractualiteAction(Request $request,$id)
    {
        $em = $this->getDoctrine()->getEntityManager();
        $actualite = $em->getRepository('AppBundle:Actualite')->find($id);
        $image = $actualite->getImage();

        $form = $this->createFormBuilder($actualite)

            ->add('titre',TextType::class , array('attr' => ['pattern' => '[a-zA-Z]+\s[a-zA-Z]*']))
            ->add('image',FileType::class,array('data_class' => null,'required' => false))
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
            else{
                $actualite->setImage($image);
            }

            $actualite->setDatePublication(new \DateTime());



            $em = $this->getDoctrine()->getEntityManager();
            $em->persist($actualite);
            $em->flush();

            $request->getSession()->getFlashBag()->add('success',' Actualité  modifiée avec succés');
            return $this->redirect($this->generateUrl('Affiche'));
        }


        return $this->render('@Admin/Default/Modifier.html.twig',array('form' => $form->createView()));
    }




    public function AfficheAction()
    {
        $em = $this->getDoctrine()->getEntityManager();
        $actualites = $em->getRepository('AppBundle:Actualite')->findAll();


        return $this->render('@Admin/Default/Affiche.html.twig',array('actualites'=>$actualites));
    }




    public function SupprimerAction(Request $request, $id)
    {

        $em = $this->getDoctrine()->getEntityManager();
        $actualite = $em->getRepository('AppBundle:Actualite')->find($id);
        if (!$actualite) {
            throw $this->createNotFoundException('No actualite found for id '.$id);
        }
        $em->remove($actualite);
        $em->flush();
        $request->getSession()->getFlashBag()->add('success', ' Delete done');
        return $this->redirect($this->generateUrl('Affiche'));
    }
    public function likedescendantAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $repository = $em->getRepository('AppBundle:Actualite');
        $query = $em->createQueryBuilder()
            ->select('v')->from('AppBundle:Actualite', 'v')
            ->orderBy('v.nbrLike', 'DESC')
            ->getQuery();

        $actualites = $query->getResult();
        return $this->render('@Admin/Default/Affiche.html.twig', array('actualites' => $actualites));
    }




    public function likeascendantAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        $repository = $em->getRepository('AppBundle:Actualite');
        $query = $em-> createQueryBuilder()
            ->select('v')->from('AppBundle:Actualite', 'v')
            ->orderBy('v.nbrLike', 'ASC')
            ->getQuery();

        $actualites = $query->getResult();
        return $this->render('@Admin/Default/Affiche.html.twig', array('actualites' => $actualites));
    }
    public function adminReclamationAction(){
        $em = $this->getDoctrine()->getEntityManager();
        $Reclamation = $em->getRepository('AppBundle:reclamation')->findAll();

        return $this->render('@Admin/Default/adminReclamation.html.twig',array('reclamation'=>$Reclamation));
    }

    public function delReclamationAction(Request $request, $reference)
    {

        $em = $this->getDoctrine()->getEntityManager();
        $reclamation = $em->getRepository('AppBundle:reclamation')->find($reference);
        if (!$reclamation) {
            throw $this->createNotFoundException('No reclamation found for id '.$reference);
        }
        $em->remove($reclamation);
        $em->flush();
        $request->getSession()->getFlashBag()->add('success', ' Delete done');
        return $this->redirect($this->generateUrl('adminReclamation'));
    }



    public function repondreAction($reference, Request $request)
    {

        $em = $this->getDoctrine()->getEntityManager();
        $reclamation=$em->getRepository(reclamation ::class)->find($reference);


        $Utilisateur=$this->getDoctrine()->getRepository(reclamation::class)->find($reference);
        $email=$Utilisateur->getEmail();
        $reclamation->setEmail($email);

        $message = \Swift_Message::newInstance()
            ->setSubject('Accusé de réception')
            ->setFrom('veloshop@zohomail.com')
            ->setTo($reclamation->getEmail())
            ->setBody('We have received your complaint. We apologize for this incident.

          Best regards.');
        $this->get('mailer')->send($message);



        $this->getDoctrine()->getManager()->persist($reclamation);

        $this->getDoctrine()->getManager()->flush();
        return $this->redirectToRoute('adminReclamation');


    }
    public function AfficherUserBackAction()
    {
        $user=$this->getDoctrine()->getRepository(User::class)->findAll();
        return $this->render('@Admin/User/user.html.twig',array('user'=>$user));
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
    public function AfficherveloAction()
    {
        $velo=$this->getDoctrine()
            ->getRepository(Velo::class)
            ->findAll();



        return $this->render('@Admin/Velo/velo.html.twig', array('v'=>$velo));
    }
    function DeleteveloAction($id){
        $em=$this->getDoctrine()->getManager();
        $velo=$em->getRepository('AppBundle:Velo')->find($id);
        $em->remove($velo);
        $em->flush();
        return $this->redirectToRoute('admin_velo');

    }
    public function UpdateveloAction(Request $request,$id)
    {
        $em = $this->getDoctrine()->getEntityManager();

        $velo = $em->getRepository('AppBundle:Velo')->find($id);

        $form = $this->createFormBuilder($velo)
            ->add('date_circulation', DateType::class, array('data' => new \DateTime(), 'widget' => 'single_text'))
            ->add('datePublication', DateType::class, array('data' => new \DateTime(), 'widget' => 'single_text'))
            ->add('price')
            ->add('description')
            ->add('localitsation_velo')
            ->add('quantity')

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
            $velo->setprice($form['price']->getData());
            $velo->setdescription($form['description']->getData());
            $velo->setLocalitsationVelo($form['localitsation_velo']->getData());
            $velo->setQuantity($form['quantity']->getData());

            $velo->setUser($this->getUser());
            $velo->setcategories($form['categories']->getData());

            $velo->setEtatLocation(0);
            $velo->setetatVendu(1);
            $velo->setUser($this->getUser());
            $em = $this->getDoctrine()->getEntityManager();
            $em->persist($velo);
            $em->flush();
            return $this->redirect($this->generateUrl('admin_velo'));


        }
        return $this->render('@Admin/Velo/Update.html.twig', array('form' => $form->createView()));

    }
    public function AfficherproduitsAction()
    {
        $produit=$this->getDoctrine()
            ->getRepository(Produit::class)
            ->findAll();

        return $this->render('@Admin/produit/produits.html.twig', array('p'=>$produit));



    }
    function DeleteproduitAction($id){
        $em=$this->getDoctrine()->getManager();
        $produits=$em->getRepository('AppBundle:Produit')->find($id);
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

        $produits = $em->getRepository('AppBundle:Produit')->find($id);

        $form = $this->createFormBuilder($produits)

            ->add('model')
            ->add('price')
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

            $produits->setPrice($form['price']->getData());
            $produits->setType($form['type']->getData());
            $em = $this->getDoctrine()->getEntityManager();
            $em->persist($produits);
            $em->flush();
            return $this->redirect($this->generateUrl('admin_ produits'));

        }
        return $this->render('@Admin/produit/Updateproduits.html.twig', array('form' => $form->createView()));

    }
}