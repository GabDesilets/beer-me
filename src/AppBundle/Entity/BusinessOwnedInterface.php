<?php

namespace AppBundle\Entity;

interface BusinessOwnedInterface
{
    /**
     * Get the business owning the entity
     *
     * @return Business
     */
    public function getBusiness();
}
