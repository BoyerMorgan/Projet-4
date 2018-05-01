<?php

namespace Louvre\BackendBundle\Validator;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */
class Checksunday extends Constraint
{
    public $message = "Il n'est pas possible de réserver de billets pour un dimanche.";

}