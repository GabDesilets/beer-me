<?php

namespace AppBundle\Event;

use AppBundle\Entity\BusinessRequest;

abstract class AbstractBusinessRequestEvent
{
    /** @var BusinessRequest */
    private $businessRequest;

    public function __construct($businessRequest)
    {
        $this->businessRequest = $businessRequest;
    }

    /**
     * @return BusinessRequest
     */
    public function getBusinessRequest()
    {
        return $this->businessRequest;
    }
}
