<?php

namespace AppBundle\Command\Business\Beer\Category;

use Symfony\Component\Validator\Constraints as Assert;
use AppBundle\Validator\Constraints as AppBundleAssert;

/**
 * Command to edit an existing business beer category
 *
 * @AppBundleAssert\UniqueBusinessBeerCategory()
 */
class EditBusinessBeerCategoryCommand
{
    /**
     * The identifier of the business beer category
     *
     * @var integer
     * @Assert\Type(type="integer")
     */
    public $id;

    /**
     * Category name
     *
     * @var string
     * @Assert\NotBlank()
     */
    public $name;
}
