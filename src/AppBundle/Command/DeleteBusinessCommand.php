<?php

namespace AppBundle\Command;

use Symfony\Component\Validator\Constraints as Assert;

/**
 * Command to delete a business
 */
class DeleteBusinessCommand
{
    /**
     * The identifier of the business to delete
     *
     * @var integer
     * @Assert\Type(type="integer")
     */
    public $id;
}
