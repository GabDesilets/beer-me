<?php

namespace AppBundle\Validator\Constraints;

use AppBundle\Command\EditBusinessBeerCategoryCommand;
use AppBundle\Command\EditBusinessCommand;
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
        // Get the id of the current business to exclude from the comparison
        $currentBusinessId = $value instanceof EditBusinessBeerCategoryCommand
            ? $value->id
            : null;

        // Only support the new validation interface
        if ($this->context instanceof ExecutionContextInterface) {
            if ($this->businessExists('name', $value->name, $currentBusinessId)) {
                $this->context->buildViolation('Not unique')
                    ->atPath('name')
                    ->addViolation();
            }
        }
    }

    /**
     * Check if another business is found with the same value for the specified field
     *
     * @param string $field The entity field
     * @param string $value The value to compare
     * @param integer|null $compareId The id of a business to ignore.  Null if no business should be ignored
     * @return bool
     */
    private function businessExists($field, $value, $compareId)
    {
        $businesses = $this->em->getRepository('AppBundle:BusinessBeerCategory')->findBy([$field => $value]);
        $business = $businesses ? $businesses[0] : null;

        return $business && $business->getId() != $compareId;
    }
}
