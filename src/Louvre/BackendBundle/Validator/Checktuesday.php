<?php

namespace Louvre\BackendBundle\Validator;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */
class Checktuesday extends Constraint
{
    public $message = "Le musée est fermé tous les mardi de l'année.";

}