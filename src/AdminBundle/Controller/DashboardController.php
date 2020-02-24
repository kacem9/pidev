<?php

namespace AdminBundle\Controller;
use AppBundle\Entity\Actualite;
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
    public function AfficherUserAction()
    {




        return $this->render('@Admin/Default/user.html.twig');
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



}