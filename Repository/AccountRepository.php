<?php

namespace MadForWebs\AccountBundle\Repository;

/**
 * AccountRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class AccountRepository extends \Doctrine\ORM\EntityRepository
{

    public function findByParameters($parameters)
    {


        return $this->createQueryBuilder('r')

            ->where('r.year = :year')
            ->setParameter('year', $parameters['year']);
//            ->addOrderBy('r.number', 'ASC');

//        return null;
    }
}
