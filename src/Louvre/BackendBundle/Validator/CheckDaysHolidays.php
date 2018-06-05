<?php

namespace Louvre\BackendBundle\Validator;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */
class CheckDaysHolidays extends Constraint
{
    public $message = "musee.ferme.jours.feries";

}