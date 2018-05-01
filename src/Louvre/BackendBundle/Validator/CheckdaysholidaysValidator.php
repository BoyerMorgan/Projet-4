<?php

namespace Louvre\BackendBundle\Validator;


use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Constraint;

class CheckdaysholidaysValidator extends ConstraintValidator
{

    public function validate($date, Constraint $constraint)
    {
        $checkDate = $date->format('dm');

        if (   $checkDate === "0105"
            || $checkDate === "0111"
            || $checkDate === "2512"
            || $checkDate === "0805"
            || $checkDate === "1407"
            )
        {
            $this->context->addViolation($constraint->message);
        }
    }
}