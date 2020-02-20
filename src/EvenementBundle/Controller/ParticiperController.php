<?php


namespace EvenementBundle\Controller;


use AppBundle\Entity\Event;
use AppBundle\Entity\Participation;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class ParticiperController extends Controller
{
    public function ParticiperAction(Request $request)
    {
        $event=new Event();
        $event = $this->getDoctrine()->getManager();
        $Events= $event->getRepository(Event::class)->findAll();

        $em= $this->getDoctrine()->getManager();
        $user=$this->getUser();
        $idu=$user->getId();
        $id=$request->get('id');
        $Event=$em->getRepository(Event::class)->find($id);
        $participation=$em->getRepository(Participation::class)->findOneBy(array('user'=>$user,'event'=>$id));
        $datej=new \DateTime('now');
        $week=date("Y-m-d", strtotime("-1 week"));
        $query = $em->createQuery(
            'SELECT e
             FROM AppBundle:Event e
             WHERE e.dateEvent >=:d1 '
        );
        $query->setParameter('d1',$week);

        $events = $query->getResult();

        if(empty($participation)){

            $participation=new Participation();
            $Event->setNbrParticipant($Event->getNbrParticipant() + 1);
            $participation-> setUser($user);
            $participation-> setEvent($Event);
            $em->persist($participation);
            $em->persist($Event);
            $em->flush();
            $em=$this->getDoctrine()->getManager();
            $etat=1;
            $Events=$em->getRepository(Event::class)->TrieEvents($etat);
            $idu=$Event->getUser();
            $nom=$Event->getNom();
            $date=$Event->getDateEvent();
            $photo=$Event->getPhoto();
            $description=$Event->getDescription();
            if(empty($participation) )
            {
                if ($idu == $user)
                {
                    $part = 0;
                    $x = 1;
                }
                else
                { $part = 0;
                    $x = 0;
                }
            }
            else
            {
                if ($idu == $user)
                {
                    $part = 1;
                    $x = 1;
                }
                else
                { $part = 1;
                    $x = 0;
                }
            }
            return $this->render('@Evenement/participe/detailEvent.html.twig', array(
                'x'=>$x,
                'part'=>$part,
                'nom' => $nom,
                'Date' =>$date ,
                'photo' => $photo,
                'desc' => $description,
                'event' => $Event));

            ?><script>alert('Merci d avoir participer à cet évènement);</script><?php
        }


        ?><script>alert('Vous avez deja participé à cet évènement ^_^');</script><?php
         $msg  =  'Vous avez déjà annuler votre participation!';


         $em=$this->getDoctrine()->getManager();
         $em->remove($participation);
         $Event->setNbreparticipation($Event->getNbreparticipation() - 1);
         $em->persist($Event);
         $em->flush();
         $etat=1;
         $events=$em->getRepository(Event::class)->TrierEventsClient($etat);
        $idu=$Event->getUser();
        $nom=$Event->getNom();
        $date=$Event->getDateEvent();
        $photo=$Event->getPhoto();
        $description=$Event->getDescription();
        if(empty($participation) )
        {
            if ($idu == $user)
            {
                $part = 0;
                $x = 1;
            }
            else
            { $part = 0;
                $x = 0;
            }
        }
        else
        {
            if ($idu == $user)
            {
                $part = 1;
                $x = 1;
            }
            else
            { $part = 1;
                $x = 0;
            }
        }
        return $this->render('BaseBundle:Events:detailEvent.html.twig', array(
            'x'=>$x,
            'part'=>$part,
            'nom' => $nom,
            'Date' =>$date ,
            'photo' => $photo,
            'desc' => $description,
            'event' => $Event));

    }
}