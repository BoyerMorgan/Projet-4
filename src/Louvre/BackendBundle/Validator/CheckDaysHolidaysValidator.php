<?php

namespace Louvre\BackendBundle\Validator;


use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Constraint;

class CheckDaysHolidaysValidator extends ConstraintValidator
{
    function dimanchePaques($year)
    {
        return date("Y-m-d", easter_date($year));
    }

    function lundiPaques($year)
    {
        $dimanchePaques = $this->dimanchePaques($year);
        return date("Y-m-d", strtotime("$dimanchePaques +1 day"));
    }
    function jeudiAscension($year)
    {
        $dimanchePaques = $this->dimanchePaques($year);
        return date("Y-m-d", strtotime("$dimanchePaques +39 day"));
    }
    function lundiPentecote($year)
    {
        $dimanchePaques = $this->dimanchePaques($year);
        return date("Y-m-d", strtotime("$dimanchePaques +50 day"));

    }
    public function validate($date, Constraint $constraint, $year = null)
    {
        $checkDate = $date->format('Y-d-m');

        if ($year === null)
        {
            $year = intval(strftime('%Y'));
        }


        if (   $checkDate === $year."-01-05"
            || $checkDate === $year."-01-11"
            || $checkDate === $year."-25-12"
            || $checkDate === $year."-08-05"
            || $checkDate === $year."-14-07"
            || $checkDate === $year."-01-01"
            || $checkDate === $year."-15-08"
            || $checkDate === $year."-11-11"
            || $checkDate === $this->lundiPaques($year)
            || $checkDate === $this->jeudiAscension($year)
            || $checkDate === $this->lundiPentecote($year)
            )
        {
            $this->context->addViolation($constraint->message);
        }
    }
}