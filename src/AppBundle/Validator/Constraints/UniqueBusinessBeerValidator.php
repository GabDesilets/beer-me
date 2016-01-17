<?php

namespace AppBundle\Validator\Constraints;

use AppBundle\Command\Business\Beer\CreateBusinessBeerCommand;
use AppBundle\Command\Business\Beer\EditBusinessBeerCommand;
use AppBundle\Entity\Business;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Context\ExecutionContextInterface;

/**
 * Apply the business beer uniqueness validation
 *
 * @package AppBundle\Validator\Constraints
 */
class UniqueBusinessBeerValidator extends ConstraintValidator
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
        $currentBusinessId = $value instanceof EditBusinessBeerCommand
            ? $value->id
            : null;

        $business = $this->getBusiness($value);

        // Only support the new validation interface
        if ($this->context instanceof ExecutionContextInterface) {
            if ($this->businessExists('name', $value->name, $currentBusinessId, $business)) {
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
    private function businessExists($field, $value, $compareId, $business)
    {
        $beers = $this->em->getRepository('AppBundle:BusinessBeer')->findBy([$field => $value, 'business' => $business]);

        // Since the field is unique the array will always contains none or only one instance
        $beer = $beers ? $beers[0] : null;

        return $beer && $beer->getId() != $compareId;
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
        if ($value instanceof CreateBusinessBeerCommand) {
            return $value->category->getBusiness();
        }

        if ($value instanceof EditBusinessBeerCommand) {
            $beer = $this->em->getRepository('AppBundle:BusinessBeer')->find($value->id);
            return $beer
                ? $beer->getBusiness()
                : null;
        }

        return null;
    }
}
