<?php

namespace AppBundle\Command\Business;

use Symfony\Component\Validator\Constraints as Assert;

/**
 * Command to edit an existing business
 */
class EditBusinessCommand extends CreateBusinessCommand
{
    /**
     * The identifier of the business
     *
     * @var integer
     * @Assert\Type(type="integer")
     */
    public $id;
}
