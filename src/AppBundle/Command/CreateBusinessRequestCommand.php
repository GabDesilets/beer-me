<?php

namespace AppBundle\Command;

use Symfony\Component\Validator\Constraints as Assert;
use AppBundle\Validator\Constraints as AppBundleAssert;

class CreateBusinessRequestCommand
{

    /**
     * @Assert\NotBlank()
     * @Assert\Length(
     *      min = 1,
     *      max = 255,
     *      minMessage = "bsn.request.first_name_min",
     *      maxMessage = "bsn.request.first_name_max"
     * )
     */
    private $firstName;

    /**
     * @Assert\NotBlank()
     * @Assert\Length(
     *      min = 1,
     *      max = 255,
     *      minMessage = "bsn.request.last_name_min",
     *      maxMessage = "bsn.request.last_name_max"
     * )
     */
    private $lastName;

    /**
     * @Assert\NotBlank()
     * @Assert\Length(
     *      min = 1,
     *      max = 255,
     *      minMessage = "bsn.request.business_name_min",
     *      maxMessage = "bsn.request.business_name_max"
     * )
     */
    private $businessName;

    /**
     * @Assert\NotBlank()
     * @Assert\Length(
     *      min = 1,
     *      max = 255,
     *      minMessage = "bsn.request.email_min",
     *      maxMessage = "bsn.request.email_max"
     * )
     */
    private $email;

    /**
     * @Assert\NotBlank()
     * @Assert\Length(
     *      min = 1,
     *      max = 255,
     *      minMessage = "bsn.request.address_min",
     *      maxMessage = "bsn.request.address_max"
     * )
     */
    private $address;

    /**
     * @Assert\NotBlank()
     * @Assert\Length(
     *      min = 1,
     *      max = 255,
     *      minMessage = "bsn.request.zip_code_min",
     *      maxMessage = "bsn.request.zip_code_max"
     * )
     */
    private $zipCode;

    /**
     * @Assert\NotBlank()
     * @Assert\Length(
     *      min = 1,
     *      max = 255,
     *      minMessage = "bsn.request.country_min",
     *      maxMessage = "bsn.request.country_max"
     * )
     */
    private $country;

    /**
     * @Assert\NotBlank()
     * @Assert\Length(
     *      min = 1,
     *      max = 255,
     *      minMessage = "bsn.request.province_min",
     *      maxMessage = "bsn.request.province_max"
     * )
     */
    private $province;

    /**
     * @Assert\NotBlank()
     * @Assert\Length(
     *      min = 1,
     *      max = 255,
     *      minMessage = "bsn.request.city_min",
     *      maxMessage = "bsn.request.city_max"
     * )
     */
    private $city;

    /**
     * @Assert\NotBlank()
     * @Assert\Length(
     *      min = 1,
     *      max = 255,
     *      minMessage = "bsn.request.phone_one_min",
     *      maxMessage = "bsn.request.phone_one_max"
     * )
     */
    private $phoneOne;

    /**
     * @Assert\NotBlank()
     * @Assert\Length(
     *      min = 1,
     *      max = 255,
     *      minMessage = "bsn.request.phone_two_min",
     *      maxMessage = "bsn.request.phone_two_max"
     * )
     */
    private $phoneTwo;

    /**
     * @Assert\NotBlank()
     * @Assert\Length(
     *      min = 1,
     *      max = 255,
     *      minMessage = "bsn.request.phone_three_min",
     *      maxMessage = "bsn.request.phone_three_max"
     * )
     */
    private $phoneThree;

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
        $this->zipCode = $zipCode;
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
     * @return mixed
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
