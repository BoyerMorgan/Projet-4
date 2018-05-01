<?php

namespace Louvre\BackendBundle\Validator;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Louvre\BackendBundle\Entity\Command;

class HourValidator extends ConstraintValidator
{
    /**
     * Checks if the passed value is valid.
     *
     * @param Command $order The value that should be validated
     * @param Constraint $constraint The constraint for the validation
     */
    public function validate ($order, Constraint $constraint)
    {
        if(!$order instanceof Command){
            return;
        }

        $reservationDate = $order->getVisitDate();

        $actualDate = new \DateTime("now", new \DateTimeZone('Europe/Paris'));

        if (!$order->getHalfDay()
            && $reservationDate->format('Ymd') === $actualDate->format('Ymd')
            && $actualDate->format('H') >= 14
        )
        {
            $this->context->buildViolation($constraint->message)
                ->atPath('visitDate')
                ->addViolation();
        }

    }
}