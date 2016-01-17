<?php

namespace AppBundle\Command\Business\Beer;

use Symfony\Component\Validator\Constraints as Assert;
use AppBundle\Entity\BusinessBeerCategory;
use AppBundle\Validator\Constraints as AppBundleAssert;

/**
 * Command to create a new business beer
 *
 * @AppBundleAssert\UniqueBusinessBeer()
 */
class CreateBusinessBeerCommand
{
    /**
     * Beer category
     *
     * @var BusinessBeerCategory
     * @Assert\NotNull()
     */
    public $category;

    /**
     * Category name
     *
     * @var string
     * @Assert\NotBlank()
     */
    public $name;

    /**
     * Category notes
     *
     * @var string
     * @Assert\NotNull()
     */
    public $notes;
}
