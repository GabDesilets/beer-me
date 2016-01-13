<?php

namespace AppBundle\Command\BusinessRequest;

use Symfony\Component\Validator\Constraints as Assert;
use AppBundle\Validator\Constraints as AppBundleAssert;

/**
 * Class CreateBusinessRequestCommand
 * @package AppBundle\Command
 */
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
    public $firstName;

    /**
     * @Assert\NotBlank()
     * @Assert\Length(
     *      min = 1,
     *      max = 255,
     *      minMessage = "bsn.request.last_name_min",
     *      maxMessage = "bsn.request.last_name_max"
     * )
     */
    public $lastName;

    /**
     * @Assert\NotBlank()
     * @Assert\Length(
     *      min = 1,
     *      max = 255,
     *      minMessage = "bsn.request.business_name_min",
     *      maxMessage = "bsn.request.business_name_max"
     * )
     */
    public $businessName;

    /**
     * @Assert\NotBlank()
     * @Assert\Length(
     *      min = 1,
     *      max = 255,
     *      minMessage = "bsn.request.email_min",
     *      maxMessage = "bsn.request.email_max"
     * )
     */
    public $email;

    /**
     * @Assert\NotBlank()
     * @Assert\Length(
     *      min = 1,
     *      max = 255,
     *      minMessage = "bsn.request.address_min",
     *      maxMessage = "bsn.request.address_max"
     * )
     */
    public $address;

    /**
     * @Assert\NotBlank()
     * @Assert\Length(
     *      min = 1,
     *      max = 6,
     *      minMessage = "bsn.request.zip_code_min",
     *      maxMessage = "bsn.request.zip_code_max"
     * )
     */
    public $zipCode;

    /**
     * @Assert\NotBlank()
     * @Assert\Length(
     *      min = 1,
     *      max = 255,
     *      minMessage = "bsn.request.country_min",
     *      maxMessage = "bsn.request.country_max"
     * )
     */
    public $country;

    /**
     * @Assert\NotBlank()
     * @Assert\Length(
     *      min = 1,
     *      max = 255,
     *      minMessage = "bsn.request.province_min",
     *      maxMessage = "bsn.request.province_max"
     * )
     */
    public $province;

    /**
     * @Assert\NotBlank()
     * @Assert\Length(
     *      min = 1,
     *      max = 255,
     *      minMessage = "bsn.request.city_min",
     *      maxMessage = "bsn.request.city_max"
     * )
     */
    public $city;

    /**
     * @Assert\NotBlank()
     * @Assert\Length(
     *      min = 1,
     *      max = 255,
     *      minMessage = "bsn.request.phone_one_min",
     *      maxMessage = "bsn.request.phone_one_max"
     * )
     */
    public $phoneOne;

    /**
     * @Assert\NotBlank()
     * @Assert\Length(
     *      min = 1,
     *      max = 255,
     *      minMessage = "bsn.request.phone_two_min",
     *      maxMessage = "bsn.request.phone_two_max"
     * )
     */
    public $phoneTwo;

    /**
     * @Assert\NotBlank()
     * @Assert\Length(
     *      min = 1,
     *      max = 255,
     *      minMessage = "bsn.request.phone_three_min",
     *      maxMessage = "bsn.request.phone_three_max"
     * )
     */
    public $phoneThree;
    
}
