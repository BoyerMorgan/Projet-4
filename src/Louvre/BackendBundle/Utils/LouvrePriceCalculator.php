<?php

namespace Louvre\BackendBundle\Utils;

use Louvre\BackendBundle\Entity\Command;

class LouvrePriceCalculator
{
    const AGE_ADULTE = 12;
    const AGE_SENIOR = 60;
    const AGE_ENFANT = 4;
    const TARIf_DEMIE_JOURNEE = 0.5;
    const PLEIN_TARIF = 16;
    const TARIF_GRATUIT = 0;
    const TARIF_ENFANT = 8;
    const TARIF_REDUIT = 10;
    const TARIF_SENIOR = 12;

    /**
     * @param Command $order
     * @return int
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

            if (intval($age) >= self::AGE_ADULTE && intval($age) < self::AGE_SENIOR && !$ticket->getReduced()) {
                $price = self::PLEIN_TARIF;
                $total += self::PLEIN_TARIF;
            } elseif (intval($age) <= self::AGE_ENFANT) {
                $price = self::TARIF_GRATUIT;
                $total += self::TARIF_GRATUIT;
            } elseif ($ticket->getReduced() && intval($age) <= self::AGE_ENFANT) {
                $price = self::TARIF_GRATUIT;
                $total += self::TARIF_GRATUIT;
            } elseif ($ticket->getReduced() && intval($age) >= self::AGE_ENFANT && intval($age) <= self::AGE_ADULTE) {
                $price = self::TARIF_ENFANT;
                $total += self::TARIF_ENFANT;
            } elseif ($ticket->getReduced() && intval($age) >= self::AGE_ADULTE) {
                $price = self::TARIF_REDUIT;
                $total += self::TARIF_REDUIT;
            } elseif (intval($age) >= self::AGE_SENIOR) {
                $price = self::TARIF_SENIOR;
                $total += self::TARIF_SENIOR;
            } else {
                $price = self::TARIF_ENFANT;
                $total += self::TARIF_ENFANT;
            }
            if ($order->getHalfDay() == false) {
                $finalPrice = $price;
            }
            else {
                $finalPrice = $price*self::TARIf_DEMIE_JOURNEE;
            }
            $ticket->setPrice($finalPrice);
        }
        if ($order->getHalfDay() == false) {
            $finalTotal = $total;
        }
        else {
            $finalTotal = $total*self::TARIf_DEMIE_JOURNEE;
        }

        $order->setPrice($finalTotal);

        return $finalTotal;

    }
}