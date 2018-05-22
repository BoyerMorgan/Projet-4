<?php

namespace Louvre\BackendBundle\Validator;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */
class Hour extends Constraint
{
    public $message = 'Vous ne pouvez pas prendre billet journée après 14h. Merci de cocher le bouton "Billet demi-journée" ou de choisir une autre date';

    public function getTargets()
    {
        return self::CLASS_CONSTRAINT; // TODO: Change the autogenerated stub
    }



}