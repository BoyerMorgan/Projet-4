<?php

namespace Louvre\BackendBundle\Validator;


use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Constraint;

class CheckdayValidator extends ConstraintValidator
{

        public function validate($date, Constraint $constraint)
    {
        $checkDate = $date->format('dm');

        if ($date->format('N')=== 2
            || $checkDate === "0105"
            || $checkDate === "0111"
            || $checkDate === "2512")
        {
            $this->context->addViolation($constraint->message);
        }
    }
}