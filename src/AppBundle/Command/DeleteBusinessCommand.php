<?php

namespace AppBundle\Command;

use Symfony\Component\Validator\Constraints as Assert;

class DeleteBusinessCommand
{
    /**
     * @Assert\Type(type="integer")
     */
    public $id;
}
