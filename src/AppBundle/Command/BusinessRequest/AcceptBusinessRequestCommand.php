<?php

namespace AppBundle\Command\BusinessRequest;

use Symfony\Component\Validator\Constraints as Assert;

/**
 * Command to accept a business request
 */
class AcceptBusinessRequestCommand
{
    /**
     * The identifier of the business request to accept
     *
     * @var integer
     * @Assert\Type(type="integer")
     */
    public $id;
}
