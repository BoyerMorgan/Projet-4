<?php

namespace Louvre\BackendBundle\PriceCalculator;

use Louvre\BackendBundle\Entity\Command;

class LouvrePricecalculator
{
    /**
     * @param object $command
     *
     * @return array
     */
    public function commandPrice(Command $command)
    {
        $total = 0;
        $number = 0;
        $dateActual = $command->getVisitDate();
        $tickets = $command->getTickets();

        foreach ($tickets as $ticket)
        {
            $number++;
            $birthdayDate = date("Y-m-d", strtotime($ticket->getBirthDate()));

            $dateNaissance = new \DateTime($birthdayDate);
            $interval = $dateNaissance->diff($dateActual);
            $age = $interval->format('%Y');

            if (intval($age) >= 12 && intval($age) < 60 && !$ticket->getReduced()) {
                $price['ticket'.$number] = 16;
                $total += 16;
            } elseif (intval($age) <= 4) {
                $price['ticket'.$number] = 0;
                $total += 0;
            } elseif ($ticket->getReduced()) {
                $price['ticket'.$number] = 10;
                $total += 10;
            } elseif(intval($age) >= 60) {
                $price['ticket'.$number] = 12;
                $total += 12;
            } else {
                $price['ticket'.$number] = 8;
                $total += 8;
            }
        }

        $price['total'] = $total;

        return $price;

    }
}