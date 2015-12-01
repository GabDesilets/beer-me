<?php

namespace AppBundle\Event;

use AppBundle\Entity\BusinessRequest;

abstract class AbstractBusinessRequestEvent
{
    /** @var BusinessRequest */
    private $entity;

    public function __construct($entity)
    {
        $this->entity = $entity;
    }

    /**
     * @return BusinessRequest
     */
    public function getEntity()
    {
        return $this->entity;
    }
}
