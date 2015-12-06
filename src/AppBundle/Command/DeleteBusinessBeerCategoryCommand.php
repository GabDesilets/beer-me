<?php

namespace AppBundle\Command;

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
