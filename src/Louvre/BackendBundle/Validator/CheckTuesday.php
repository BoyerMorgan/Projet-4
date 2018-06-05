<?php

namespace Louvre\BackendBundle\Validator;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */
class CheckTuesday extends Constraint
{
    public $message = "musee.ferme.mardi";

}