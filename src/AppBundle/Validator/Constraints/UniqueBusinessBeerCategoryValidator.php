<?php

namespace AppBundle\Validator\Constraints;

use AppBundle\Command\CreateBusinessBeerCategoryCommand;
use AppBundle\Command\EditBusinessBeerCategoryCommand;
use AppBundle\Entity\Business;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Context\ExecutionContextInterface;

/**
 * Apply the business beer category uniqueness validation
 *
 * @package AppBundle\Validator\Constraints
 */
class UniqueBusinessBeerCategoryValidator extends ConstraintValidator
{
    /** @var EntityManagerInterface */
    private $em;

    /**
     * UniqueBusinessValidator constructor.
     * @param EntityManagerInterface $em
     */
    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    /**
     * Checks if the passed value is valid.
     *
     * @param mixed $value The value that should be validated
     * @param Constraint $constraint The constraint for the validation
     */
    public function validate($value, Constraint $constraint)
    {
        // Get the id of the current category to exclude from the comparison
        $currentCategoryId = $value instanceof EditBusinessBeerCategoryCommand
            ? $value->id
            : null;

        // Get the business related to the command
        $business = $this->getBusiness($value);

        // Only support the new validation interface
        if ($this->context instanceof ExecutionContextInterface) {
            if ($this->categoryExists('name', $value->name, $currentCategoryId, $business)) {
                $this->context->buildViolation('Not unique')
                    ->atPath('name')
                    ->addViolation();
            }
        }
    }

    /**
     * Check if another category is found with the same value for the specified field for the same business
     *
     * @param string $field The entity field
     * @param string $value The value to compare
     * @param integer|null $compareId The id of a category to ignore.  Null if no business should be ignored
     * @param Business $business
     * @return bool
     */
    private function categoryExists($field, $value, $compareId, $business)
    {
        $categories = $this->em->getRepository('AppBundle:BusinessBeerCategory')
            ->findBy([$field => $value, 'business' => $business]);

        // Since the field is unique the array will always contains none or only one instance
        $category = $categories ? $categories[0] : null;

        return $category && $category->getId() != $compareId;
    }

    /**
     * Get the business related to the command.
     *
     * The business is directly in the command for a creation or obtainable with the id for an edition
     *
     * @param $value
     * @return Business|null
     */
    private function getBusiness($value)
    {
        if ($value instanceof CreateBusinessBeerCategoryCommand) {
            return $value->business;
        }

        if ($value instanceof EditBusinessBeerCategoryCommand) {
            $category = $this->em->getRepository('AppBundle:BusinessBeerCategory')->find($value->id);
            return $category
                ? $category->getBusiness()
                : null;
        }

        return null;
    }
}
