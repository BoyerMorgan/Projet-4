<?php

namespace Louvre\BackendBundle\Validator;


use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Constraint;

class CheckSundayValidator extends ConstraintValidator
{
    public function validate($date, Constraint $constraint)
    {
        if ($date->format('N') === "7") {
            $this->context->addViolation($constraint->message);
        }
    }
}
