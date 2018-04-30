<?php

namespace Louvre\BackendBundle\DateValidator;

use Louvre\BackendBundle\Entity\Command;
use Symfony\Component\HttpFoundation\Session\Session;


class LouvreValidator
{
    private $session;

    public function __construct(Session $session)
    {
        $this->session = $session;
    }

    public function ticketsValidator($nbTicketsSold)
    {

        $nbTicketLeft = 1000;


        if (($nbTicketLeft - $nbTicketsSold) < 0)
        {
            $this->session->getFlashBag()->add('erreur', 'Désolé, mais il ne reste plus assez de billets à vendre pour ce jour-ci');
            return false;
        }

        return true;



    }
    public function dateValidator(Command $command)
    {
        $reservationDate = $command->getVisitDate();

        $actualDate = new \DateTime("now", new \DateTimeZone('Europe/Paris'));

        if (!$command->getHalfDay()
            && $reservationDate->format('Ymd') === $actualDate->format('Ymd')
            && $actualDate->format('H') >= 14
        )
            {
               $this->session->getFlashBag()->add('erreur', 'Vous ne pouvez pas commander de billet journée passé 14h.');
               return false;
            }
        return true;

    }
}