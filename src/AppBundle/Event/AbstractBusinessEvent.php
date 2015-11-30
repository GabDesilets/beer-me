<?php

namespace AppBundle\Event;

use AppBundle\Entity\Business;

/**
 * Represent an event on a business Entity
 *
 * @package AppBundle\Event
 */
abstract class AbstractBusinessEvent
{
    /** @var Business */
    private $business;

    public function __construct($business)
    {
        $this->business = $business;
    }

    /**
     * Get the business
     *
     * @return Business
     */
    public function getBusiness()
    {
        return $this->business;
    }
}
