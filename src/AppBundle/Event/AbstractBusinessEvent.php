<?php

namespace AppBundle\Event;

use AppBundle\Entity\Business;

abstract class AbstractBusinessEvent
{
    /** @var Business */
    private $entity;

    public function __construct($entity)
    {
        $this->entity = $entity;
    }

    /**
     * @return Business
     */
    public function getEntity()
    {
        return $this->entity;
    }
}
