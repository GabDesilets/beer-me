<?php

namespace AppBundle\Command;

use AppBundle\Entity\BusinessBeerCategory;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Command to create a new business beer
 *
 * TODO Unique beer
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
