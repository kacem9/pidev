<?php

namespace AppBundle\Repository;

/**
 * EventRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class EventRepository extends \Doctrine\ORM\EntityRepository
{
    public function TrieEvents($etat)
    {
        $query = $this->getEntityManager()
            ->createQuery("
        select s  from AppBundle:Event s where s.etat like :etat AND s.dateEvent > CURRENT_DATE () ORDER BY s.dateEvent")->setParameter('etat','%'.$etat.'%');

        return $query->getResult();

    }
    public function TrierEventsClient($etat)
    {
        $query = $this->getEntityManager()
            ->createQuery("
        select s  from AppBundle:Event s where s.etat like :etat AND s.dateEvent > CURRENT_DATE () ORDER BY s.dateEvent")->setParameter('etat','%'.$etat.'%');

        return $query->getResult();

    }
}
