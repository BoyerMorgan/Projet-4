<?php

namespace Tests\Louvre\BackendBundle\Utils;

use Louvre\BackendBundle\Entity\Command;
use Louvre\BackendBundle\Entity\Tickets;
use Louvre\BackendBundle\Utils\LouvrePriceCalculator;
use PHPUnit\Framework\TestCase;

class LouvrePriceCalculatorTest extends TestCase
{



    public function ticketProvider()
    {
        // [$reduce, $dateVisit, $birthDate, $halfDay, $expected]
        return [
            [true, new \DateTime(),'1988-01-10', false, 10],
            [false, new \DateTime(),'2010-01-10', false, 8],
            [true, new \DateTime(),'2010-01-10', false, 8],
            [false, new \DateTime(),'1968-01-10', false, 16],
            [true, new \DateTime(),'2017-01-10', false, 0],
        ];
    }


    /**
     * @dataProvider ticketProvider
     *
     * @param $reduce
     * @param $dateVisit
     * @param $birthDate
     * @param $halfDay
     * @param $expected
     */
    public function testTicketPrice($reduce, $dateVisit, $birthDate, $halfDay, $expected)
    {
        $order = new Command();
        $order->setVisitDate($dateVisit);
        $order->setHalfDay($halfDay);

        $ticket = new Tickets();
        $ticket->setReduced($reduce);
        $ticket->setBirthDate($birthDate);

        $order->addTicket($ticket);


        $calculator = new LouvrePriceCalculator();

        $this->assertSame($expected, $calculator->setCommandPrice($order));


    }

}