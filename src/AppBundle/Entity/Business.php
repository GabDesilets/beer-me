<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use RandomLib\Factory;
use RandomLib\Generator;

/**
 * Business
 *
 * @ORM\Entity(repositoryClass="BusinessRepository")
 * @ORM\Table(name="businesses")
 */
class Business
{
    /** @const The length of the default password that is used with a new instance */
    const DEFAULT_PASSWORD_LENGTH = 32;
    /**
     * @var integer
     *
     * @ORM\Id
     * @ORM\Column(name="id", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255)
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="address", type="string", length=255)
     */
    private $address;

    /**
     * @var string
     *
     * @ORM\Column(name="phone", type="string", length=15)
     */
    private $phone;

    /**
     * @var User
     *
     * @ORM\ManyToOne(targetEntity="User", cascade={"persist", "remove"})
     * @ORM\JoinColumn(name="administrator_user_id", referencedColumnName="id")
     */
    private $administratorUser;

    public function __construct($name, $address, $phone, $administratorEmail)
    {
        $this->name = $name;
        $this->address = $address;
        $this->phone = $phone;

        $this->administratorUser = new User();
        $this->administratorUser->setEmail($administratorEmail);

        // Use a cryptographically secure library to generate a temporary password for the user account
        // Medium strength is used as a trade-off on the computation time
        $factory = new Factory();
        $password = $factory->getMediumStrengthGenerator()->generateString(
            self::DEFAULT_PASSWORD_LENGTH,
            Generator::CHAR_BASE64 | Generator::CHAR_SYMBOLS
        );

        $this->administratorUser->setPlainPassword($password);
    }

    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set name
     *
     * @param string $name
     *
     * @return Business
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set address
     *
     * @param string $address
     *
     * @return Business
     */
    public function setAddress($address)
    {
        $this->address = $address;

        return $this;
    }

    /**
     * Get address
     *
     * @return string
     */
    public function getAddress()
    {
        return $this->address;
    }

    /**
     * Set phone
     *
     * @param string $phone
     *
     * @return Business
     */
    public function setPhone($phone)
    {
        $this->phone = $phone;

        return $this;
    }

    /**
     * Get phone
     *
     * @return string
     */
    public function getPhone()
    {
        return $this->phone;
    }

    /**
     * Get administrator user
     *
     * @return User
     */
    public function getAdministratorUser()
    {
        return $this->administratorUser;
    }
}

