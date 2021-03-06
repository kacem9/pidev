<?php

namespace AppBundle\Repository;

/**
 * CommandeRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class CommandeRepository extends \Doctrine\ORM\EntityRepository
{

    public function countByMonthCommande(){
        $qb = $this->_em->createQueryBuilder();

        return $qb
            ->select('MONTH(c.dateCommande) AS month,count(c.id) as total')
            ->where('YEAR(c.dateCommande) = YEAR(CURRENT_DATE())')
            ->from($this->_entityName, 'c')
            ->groupBy('month')
            ->getQuery()
            ->getResult();
    }
}
