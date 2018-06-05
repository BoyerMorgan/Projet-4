<?php

namespace Louvre\BackendBundle\Validator;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */
class Ticket extends Constraint
{
    public $message = 'musee.plein';

    public function getTargets()
    {
        return self::CLASS_CONSTRAINT; // TODO: Change the autogenerated stub
    }


}

