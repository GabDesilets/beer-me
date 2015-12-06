<?php

namespace AppBundle\Event;

use AppBundle\Entity\BusinessBeer;

abstract class AbstractBusinessBeerEvent
{
    /** @var BusinessBeer */
    private $beer;

    public function __construct($beer)
    {
        $this->beer = $beer;
    }

    /**
     * Get the beer
     *
     * @return BusinessBeer
     */
    public function getBeer()
    {
        return $this->beer;
    }
}
