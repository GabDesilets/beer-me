<?php

namespace AppBundle\Command;

use Symfony\Component\Validator\Constraints as Assert;
use AppBundle\Validator\Constraints as AppBundleAssert;

/**
 * Command to create a new business
 *
 * @AppBundleAssert\UniqueBusiness
 */
class CreateBusinessCommand
{
    /**
     * Business name
     *
     * @var string
     * @Assert\NotBlank()
     */
    public $name;

    /**
     * Business address
     *
     * @var string
     * @Assert\NotBlank()
     */
    public $address;

    /**
     * Business phone number
     *
     * The phone number is stored as a string to allow alphanumerical numbers, extensions and such.
     *
     * @var string
     * @Assert\NotBlank()
     */
    public $phone;

    /**
     * Business administrator email
     *
     * This email is used to create the user account associated with the business.
     *
     * @var string
     * @Assert\NotBlank()
     */
    public $administratorEmail;
}
