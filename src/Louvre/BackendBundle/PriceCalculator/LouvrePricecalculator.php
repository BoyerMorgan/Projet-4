<?php

namespace Louvre\BackendBundle\PriceCalculator;

use Louvre\BackendBundle\Entity\Command;
use Symfony\Component\HttpFoundation\RequestStack;

class LouvrePricecalculator
{
    /**
     * @param object $command
     *
     * @return int
     */
    public function commandPrice(Command $command)
    {
        $total = 0;
        $dateActual = $command->getVisitDate();
        $tickets = $command->getTickets();

        foreach ($tickets as $ticket)
        {
            $birthDate = date("Y-m-d", strtotime($ticket->getBirthDate()));

            $birthDay = new \DateTime($birthDate);
            $interval = $birthDay->diff($dateActual);
            $age = $interval->format('%Y');

            if (intval($age) >= 12 && intval($age) < 60 && !$ticket->getReduced()) {
                $price = 16;
                $total += 16;
            } elseif (intval($age) <= 4) {
                $price = 0;
                $total += 0;
            } elseif ($ticket->getReduced()) {
                $price = 10;
                $total += 10;
            } elseif(intval($age) >= 60) {
                $price = 12;
                $total += 12;
            } else {
                $price = 8;
                $total += 8;
            }
            $ticket->setPrice($price);

        }

        return $total;

    }
}