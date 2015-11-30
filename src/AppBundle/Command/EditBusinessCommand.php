<?php

namespace AppBundle\Command;

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
