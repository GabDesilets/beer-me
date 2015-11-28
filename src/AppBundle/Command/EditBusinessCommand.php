<?php

namespace AppBundle\Command;

use Symfony\Component\Validator\Constraints as Assert;

class EditBusinessCommand extends CreateBusinessCommand
{
    /**
     * @Assert\Type(type="integer")
     */
    public $id;
}
