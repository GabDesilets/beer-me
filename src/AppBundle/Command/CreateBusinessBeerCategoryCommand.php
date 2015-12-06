<?php

namespace AppBundle\Command;

use AppBundle\Entity\Business;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Command to create a new business beer category
 *
 * TODO Unique beer category
 */
class CreateBusinessBeerCategoryCommand
{
    /**
     * Category business
     *
     * @var Business
     */
    public $business;

    /**
     * Category name
     *
     * @var string
     * @Assert\NotBlank()
     */
    public $name;
}
