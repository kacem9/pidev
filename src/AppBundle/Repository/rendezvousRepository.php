<?php


namespace AppBundle\Repository;


class rendezvousRepository extends \Doctrine\ORM\EntityRepository
{
    public function findAction($user)
    {

        return $this->createQueryBuilder()
            ->select( 'rendezvous' )
            ->from( 'AppBundle:rendezvous', 'rendezvous' )
            ->where( 'rendezvous.user = : id' )

            ->setParameter('id', $user)
            ->getQuery()
            ->getResult
            ;


    }
    public function finduserAction($user)
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