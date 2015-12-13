<?php

namespace AppBundle\Command;

use Symfony\Component\Validator\Constraints as Assert;

/**
 * Command to delete a business beer category
 */
class DeleteBusinessBeerCategoryCommand
{
    /**
     * The identifier of the category to delete
     *
     * @var integer
     * @Assert\Type(type="integer")
     */
    public $id;
}
