<?php

namespace AppBundle\Command;

use AppBundle\Entity\Business;
use Symfony\Component\Validator\Constraints as Assert;
use AppBundle\Validator\Constraints as AppBundleAssert;

/**
 * Command to create a new business beer category
 *
 * @AppBundleAssert\UniqueBusinessBeerCategory()
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
