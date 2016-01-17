<?php

namespace AppBundle\Command\Business\Beer;

use AppBundle\Entity\BusinessBeerCategory;
use Symfony\Component\Validator\Constraints as Assert;
use AppBundle\Validator\Constraints as AppBundleAssert;

/**
 * Command to edit an existing business beer
 *
 * @AppBundleAssert\UniqueBusinessBeer()
 */
class EditBusinessBeerCommand
{
    /**
     * The identifier of the business beer
     *
     * @var integer
     * @Assert\Type(type="integer")
     */
    public $id;

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
