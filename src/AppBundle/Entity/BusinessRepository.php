<?php

namespace AppBundle\Entity;

use Doctrine\ORM\EntityRepository;

class BusinessRepository extends EntityRepository
{
    public function findAllQuery()
    {
        return $this->createQueryBuilder('b')->getQuery();
    }
}
