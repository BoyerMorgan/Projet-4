<?php

namespace Louvre\BackendBundle\Validator;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */
class Checkday extends Constraint
{
    public $message = "Le musée est fermé à cette date-là, veuillez en choisir une autre";

}