<?php

namespace Louvre\BackendBundle\DateValidator;

use Louvre\BackendBundle\Entity\Command;
use Symfony\Component\HttpFoundation\Request;

class LouvreDateValidator
{
    public function dateValidator(Request $request, Command $command)
    {
        $session = $request->getSession();
        $reservationDate = $command->getVisitDate();

        $actualDate = new \DateTime("now", new \DateTimeZone('Europe/Paris'));

        if (!$command->getHalfDay()
            && $reservationDate->format('Ymd') === $actualDate->format('Ymd')
            && $actualDate->format('H') >= 14
        )
            {
               $session->getFlashBag()->add('erreur', 'Vous ne pouvez pas commander de billet journée passé 14h.');
               return false;
            }
        return true;

    }
}