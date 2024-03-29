<?php

namespace AppBundle\Repository;

/**
 * CommentaireProdRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class CommentaireProdRepository extends \Doctrine\ORM\EntityRepository
{
    public function fiii($x){
        $qry=$this->getEntityManager()
            ->createQuery("SELECT c FROM AppBundle:CommentaireProd c WHERE c.idProd = :x")
            ->setParameter(':x',$x);
        return $qry->getResult();
    }
    public function clc($x){
        $qry=$this->getEntityManager()
            ->createQuery("SELECT avg(c.rate) FROM AppBundle:CommentaireProd  c WHERE c.idProd = :x")
            ->setParameter(':x',$x);
        return $qry->getResult();
    }

}
