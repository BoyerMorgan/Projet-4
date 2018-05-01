<?php

namespace Louvre\BackendBundle\Validator;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */
class Checkdaysholidays extends Constraint
{
    public $message = "Le musée est fermé les jours feriés";

}