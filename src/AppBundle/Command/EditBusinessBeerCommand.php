<?php

namespace AppBundle\Command;
use AppBundle\Entity\BusinessBeerCategory;

/**
 * Command to edit an existing business beer
 *
 * TODO Unique beer category
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