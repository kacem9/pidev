<?php

namespace AppBundle\Repository;
use DoctrineExtensions\Query\Mysql\Year;
/**
 * VeloRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class VeloRepository extends \Doctrine\ORM\EntityRepository
{
    public function findArray($array)
{
    $qb = $this->createQueryBuilder('v')
        ->Select('v')
        ->Where('v.id IN (:array)')
        ->setParameter('array', $array);
    return $qb->getQuery()->getResult();
}
    public function AfficherVelo(){

        $qb = $this->createQueryBuilder('velo')
            ->Select('velo')
            ->where('Week(velo.datePublication) = Week(CURRENT_DATE())')
            ->andwhere('velo.etatVendu = 0')
            ->andwhere('velo.etatLocation = 1');
            return $qb->getQuery()->getResult();
    }

    public function countByMonthVeloLouer(){
        $qb = $this->_em->createQueryBuilder();

        return $qb
            ->select('MONTH(v.datePublication) AS month,count(v.id) as total')
            ->where('YEAR(v.datePublication) = YEAR(CURRENT_DATE())')
            ->andwhere('v.etatVendu = 0')
            ->andwhere('v.etatLocation = 1')
            ->andwhere('v.quantity = 0 ')
            ->from($this->_entityName, 'v')
            ->groupBy('month')
            ->getQuery()
            ->getResult();
    }
    public function countByMonthVelopasLouer(){
        $qb = $this->_em->createQueryBuilder();

        return $qb
            ->select('MONTH(v.datePublication) AS month,count(v.id) as total')
            ->where('YEAR(v.datePublication) = YEAR(CURRENT_DATE())')
            ->andwhere('v.etatVendu = 0')
            ->andwhere('v.etatLocation = 1')
            ->andwhere('v.quantity > 0 ')
            ->from($this->_entityName, 'v')
            ->groupBy('month')
            ->getQuery()
            ->getResult();
    }
}
