<?php

namespace AppBundle\Validator\Constraints;

use AppBundle\Command\EditBusinessCommand;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Context\ExecutionContextInterface;

class UniqueBusinessValidator extends ConstraintValidator
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
        $currentBusinessId = $value instanceof EditBusinessCommand
            ? $value->id
            : null;

        // Get the id of the current administrator user to exclude from the comparison
        $currentUserId = $currentBusinessId
            ? $this->em->getRepository('AppBundle:Business')->find($currentBusinessId)->getAdministratorUser()->getId()
            : null;

        if ($this->context instanceof ExecutionContextInterface) {
            if ($this->businessExists('name', $value->name, $currentBusinessId)) {
                $this->context->buildViolation('Not unique')
                    ->atPath('name')
                    ->addViolation();
            }

            if ($this->businessExists('address', $value->address, $currentBusinessId)) {
                $this->context->buildViolation('Not unique')
                    ->atPath('address')
                    ->addViolation();
            }

            if ($this->businessExists('phone', $value->phone, $currentBusinessId)) {
                $this->context->buildViolation('Not unique')
                    ->atPath('phone')
                    ->addViolation();
            }

            if ($this->userExists('email', $value->administratorEmail, $currentUserId)) {
                $this->context->buildViolation('Not unique')
                    ->atPath('administrator_email')
                    ->addViolation();
            }
        }
    }

    private function businessExists($field, $value, $compareId)
    {
        $businesses = $this->em->getRepository('AppBundle:Business')->findBy([$field => $value]);
        $business = $businesses ? $businesses[0] : null;

        return $business && $business->getId() != $compareId;
    }

    private function userExists($field, $value, $compareId)
    {
        $users = $this->em->getRepository('AppBundle:User')->findBy([$field => $value]);
        $user = $users ? $users[0] : null;

        return $user && $user->getId() != $compareId;
    }
}
