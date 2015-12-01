<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * AccessRequests
 *
 * @ORM\Table(name="business_requests")
 * @ORM\Entity
 */
class BusinessRequest
{
    /**
     * @var string
     *
     * @ORM\Column(name="first_name", type="string", length=255, nullable=false)
     */
    private $firstName;

    /**
     * @var string
     *
     * @ORM\Column(name="last_name", type="string", length=255, nullable=false)
     */
    private $lastName;

    /**
     * @var string
     *
     * @ORM\Column(name="business_name", type="string", length=255, nullable=false)
     */
    private $businessName;

    /**
     * @var string
     *
     * @ORM\Column(name="email", type="string", length=255, nullable=false)
     */
    private $email;

    /**
     * @var string
     *
     * @ORM\Column(name="address", type="string", length=255, nullable=false)
     */
    private $address;

    /**
     * @var string
     *
     * @ORM\Column(name="zip_code", type="string", length=6, nullable=false)
     */
    private $zipCode;

    /**
     * @var string
     *
     * @ORM\Column(name="country", type="string", length=255, nullable=false)
     */
    private $country;

    /**
     * @var string
     *
     * @ORM\Column(name="province", type="string", length=255, nullable=false)
     */
    private $province;

    /**
     * @var string
     *
     * @ORM\Column(name="city", type="string", length=255, nullable=false)
     */
    private $city;

    /**
     * @var string
     *
     * @ORM\Column(name="phone", type="string", length=255, nullable=false)
     */
    private $phone;

    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var string
     */
    private $phoneOne;

    /**
     * @var string
     */
    private $phoneTwo;

    /**
     * @var string
     */
    private $phoneThree;


    /**
     * AccessRequests constructor.
     * @param string $firstName
     * @param string $lastName
     * @param string $businessName
     * @param string $email
     * @param string $address
     * @param string $zipCode
     * @param string $country
     * @param string $province
     * @param string $city
     * @param $phoneOne
     * @param $phoneTwo
     * @param $phoneThree
     */
    public function __construct(
        $firstName,
        $lastName,
        $businessName,
        $email,
        $address,
        $zipCode,
        $country,
        $province,
        $city,
        $phoneOne,
        $phoneTwo,
        $phoneThree
    ) {
        $this->firstName = $firstName;
        $this->lastName = $lastName;
        $this->businessName = $businessName;
        $this->email = $email;
        $this->address = $address;
        $this->zipCode = str_replace(' ', '', $zipCode);
        $this->country = $country;
        $this->province = $province;
        $this->city = $city;
        $this->phoneOne = $phoneOne;
        $this->phoneTwo = $phoneTwo;
        $this->phoneThree = $phoneThree;
        $this->setPhone();
    }


    /**
     * @return string
     */
    public function getFirstName()
    {
        return $this->firstName;
    }

    /**
     * @param string $firstName
     */
    public function setFirstName($firstName)
    {
        $this->firstName = $firstName;
    }

    /**
     * @return string
     */
    public function getLastName()
    {
        return $this->lastName;
    }

    /**
     * @param string $lastName
     */
    public function setLastName($lastName)
    {
        $this->lastName = $lastName;
    }

    /**
     * @return string
     */
    public function getBusinessName()
    {
        return $this->businessName;
    }

    /**
     * @param string $businessName
     */
    public function setBusinessName($businessName)
    {
        $this->businessName = $businessName;
    }

    /**
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * @param string $email
     */
    public function setEmail($email)
    {
        $this->email = $email;
    }

    /**
     * @return string
     */
    public function getAddress()
    {
        return $this->address;
    }

    /**
     * @param string $address
     */
    public function setAddress($address)
    {
        $this->address = $address;
    }

    /**
     * @return string
     */
    public function getZipCode()
    {
        return $this->zipCode;
    }

    /**
     * @param string $zipCode
     */
    public function setZipCode($zipCode)
    {
        $this->zipCode = str_replace(' ', '', $zipCode);
    }

    /**
     * @return string
     */
    public function getCountry()
    {
        return $this->country;
    }

    /**
     * @param string $country
     */
    public function setCountry($country)
    {
        $this->country = $country;
    }

    /**
     * @return string
     */
    public function getProvince()
    {
        return $this->province;
    }

    /**
     * @param string $province
     */
    public function setProvince($province)
    {
        $this->province = $province;
    }

    /**
     * @return string
     */
    public function getCity()
    {
        return $this->city;
    }

    /**
     * @param string $city
     */
    public function setCity($city)
    {
        $this->city = $city;
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getPhone()
    {
        return $this->phone;
    }

    public function setPhone()
    {
        $this->phone = join('-', [$this->phoneOne, $this->phoneTwo, $this->phoneThree]);
    }

    /**
     * @return string
     */
    public function getPhoneOne()
    {
        return $this->phoneOne;
    }


    /**
     * @param mixed $phoneOne
     */
    public function setPhoneOne($phoneOne)
    {
        $this->phoneOne = $phoneOne;
    }

    /**
     * @return mixed
     */
    public function getPhoneTwo()
    {
        return $this->phoneTwo;
    }

    /**
     * @param mixed $phoneTwo
     */
    public function setPhoneTwo($phoneTwo)
    {
        $this->phoneTwo = $phoneTwo;
    }

    /**
     * @return mixed
     */
    public function getPhoneThree()
    {
        return $this->phoneThree;
    }

    /**
     * @param mixed $phoneThree
     */
    public function setPhoneThree($phoneThree)
    {
        $this->phoneThree = $phoneThree;
    }
}