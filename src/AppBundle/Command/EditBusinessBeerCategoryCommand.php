<?php

namespace AppBundle\Command;

use Symfony\Component\Validator\Constraints as Assert;

/**
 * Command to edit an existing business beer category
 *
 * TODO Unique beer category
 */
class EditBusinessBeerCategoryCommand
{
    /**
     * The identifier of the business beer category
     *
     * @var integer
     * @Assert\Type(type="integer")
     */
    public $id;

    /**
     * Category name
     *
     * @var string
     * @Assert\NotBlank()
     */
    public $name;
}
