<?php

namespace AppBundle\Validator\Constraints;

use Symfony\Component\Validator\Constraint;

/**
 * Validate that the beer category has a unique name for the business
 *
 * @Annotation
 */
class UniqueBusinessBeerCategory extends Constraint
{
    public function getTargets()
    {
        return self::CLASS_CONSTRAINT;
    }

    public function validatedBy()
    {
        return 'app.unique_business_beer_category';
    }
}
