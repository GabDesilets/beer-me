<?php

namespace AppBundle\Command;

use Symfony\Component\Validator\Constraints as Assert;
use AppBundle\Validator\Constraints as AppBundleAssert;

/**
 * @AppBundleAssert\UniqueBusiness
 */
class CreateBusinessCommand
{

    /**
     * @Assert\NotBlank()
     */
    private $name;

    /**
     * @Assert\NotBlank()
     */
    private $address;

    /**
     * @Assert\NotBlank()
     */
    private $phone;

    /**
     * @Assert\NotBlank()
     */
    private $administratorEmail;

    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param mixed $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * @return mixed
     */
    public function getAddress()
    {
        return $this->address;
    }

    /**
     * @param mixed $address
     */
    public function setAddress($address)
    {
        $this->address = $address;
    }

    /**
     * @return mixed
     */
    public function getPhone()
    {
        return $this->phone;
    }

    /**
     * @param mixed $phone
     */
    public function setPhone($phone)
    {
        $this->phone = $phone;
    }

    /**
     * @return mixed
     */
    public function getAdministratorEmail()
    {
        return $this->administratorEmail;
    }

    /**
     * @param mixed $administratorEmail
     */
    public function setAdministratorEmail($administratorEmail)
    {
        $this->administratorEmail = $administratorEmail;
    }
}
