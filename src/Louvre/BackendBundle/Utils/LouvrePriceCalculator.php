<?php

namespace Louvre\BackendBundle\Utils;

use Louvre\BackendBundle\Entity\Command;

class LouvrePriceCalculator
{
    /**
     * @param object $order
     */
    public function setCommandPrice(Command $order)
    {
        $total = 0;
        $dateActual = $order->getVisitDate();
        $tickets = $order->getTickets();

        foreach ($tickets as $ticket) {
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
            } elseif ($ticket->getReduced() && intval($age) <= 4) {
                $price = 0;
                $total += 0;
            } elseif ($ticket->getReduced() && intval($age) >= 12) {
                $price = 10;
                $total += 10;
            } elseif (intval($age) >= 60) {
                $price = 12;
                $total += 12;
            } else {
                $price = 8;
                $total += 8;
            }
            $ticket->setPrice($price);

        }
        $order->setPrice($total);

    }
}