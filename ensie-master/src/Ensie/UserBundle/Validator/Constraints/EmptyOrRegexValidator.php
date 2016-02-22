<?php
/**
 * Created by PhpStorm.
 * User: Badjak
 * Date: 14-9-14
 * Time: 14:18
 */

namespace Ensie\UserBundle\Validator\Constraints;


use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class EmptyOrRegexValidator extends ConstraintValidator{

    public function validate($value, Constraint $constraint)
    {
        /** @var EmptyOrRegex $constraint */
        if (!empty($value) and !preg_match($constraint->pattern, $value, $matches)) {
            $this->context->addViolation(
                $constraint->message
            );
        }
    }
} 