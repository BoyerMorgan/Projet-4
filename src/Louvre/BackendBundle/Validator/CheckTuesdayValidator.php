<?php

namespace Louvre\BackendBundle\Validator;


use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Constraint;

class CheckTuesdayValidator extends ConstraintValidator
{
    public function validate($date, Constraint $constraint)
    {
        if ($date->format('N') === "2") {
            $this->context->addViolation($constraint->message);
        }
    }
}
