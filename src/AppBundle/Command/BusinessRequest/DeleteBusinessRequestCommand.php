<?php

namespace AppBundle\Command\BusinessRequest;

use Symfony\Component\Validator\Constraints as Assert;

/**
 * Command to delete a business
 */
class DeleteBusinessRequestCommand
{
    /**
     * The identifier of the business request to delete
     *
     * @var integer
     * @Assert\Type(type="integer")
     */
    public $id;
}
