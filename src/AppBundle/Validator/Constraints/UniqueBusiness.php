<?php

namespace AppBundle\Validator\Constraints;

use Symfony\Component\Validator\Constraint;

/**
 * Validate that the business has unique name, phone, address and administrator email
 *
 * @Annotation
 */
class UniqueBusiness extends Constraint
{
    public function getTargets()
    {
        return self::CLASS_CONSTRAINT;
    }

    public function validatedBy()
    {
        return 'app.unique_business';
    }
}
