<?php

namespace AppBundle\Command\Business\Beer;

use Symfony\Component\Validator\Constraints as Assert;

/**
 * Command to delete a business beer
 */
class DeleteBusinessBeerCommand
{
    /**
     * The identifier of the beer to delete
     *
     * @var integer
     * @Assert\Type(type="integer")
     */
    public $id;
}
