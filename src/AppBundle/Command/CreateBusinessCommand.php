<?php

namespace AppBundle\Command;

class CreateBusinessCommand
{
    private $name;
    private $address;
    private $phone;
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
