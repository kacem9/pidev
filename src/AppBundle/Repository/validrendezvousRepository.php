<?php


namespace AppBundle\Repository;


class validrendezvousRepository extends \Doctrine\ORM\EntityRepository
{
    public function findAction($user)
    {

        return $this->createQueryBuilder()
            ->select( 'rendezvous' )
            ->from( 'AppBundle:rendezvous', 'rendezvous' )
            ->where( 'rendezvous.id = : id' )

            ->setParameter('id', $user)
            ->getQuery()
            ->getResult
            ;


    }

}