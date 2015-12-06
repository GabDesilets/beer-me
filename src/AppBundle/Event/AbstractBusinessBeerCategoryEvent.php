<?php

namespace AppBundle\Event;

use AppBundle\Entity\BusinessBeerCategory;

abstract class AbstractBusinessBeerCategoryEvent
{
    /** @var BusinessBeerCategory */
    private $category;

    public function __construct($category)
    {
        $this->category = $category;
    }

    /**
     * Get the beer category
     *
     * @return BusinessBeerCategory
     */
    public function getCategory()
    {
        return $this->category;
    }
}
