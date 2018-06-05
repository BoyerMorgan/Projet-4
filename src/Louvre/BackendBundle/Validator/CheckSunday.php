<?php

namespace Louvre\BackendBundle\Validator;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */
class CheckSunday extends Constraint
{
    public $message = "musee.ferme.dimanche";

}