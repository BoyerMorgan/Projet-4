<?php

namespace Tests\Louvre\BackendBundle\Utils;

use Louvre\BackendBundle\Entity\Command;
use Louvre\BackendBundle\Entity\Tickets;
use Louvre\BackendBundle\Utils\LouvrePriceCalculator;
use PHPUnit\Framework\TestCase;

class LouvrePriceCalculatorTest extends TestCase
{

//    public function testSetCommandPrice ()
//    {
//        $order = new Command();
//
//        $ticket1 = new Tickets();
//        $ticket2 = new Tickets();
//        $ticket3 = new Tickets();
//
//        $ticket1->setReduced(true);
//        $ticket2->setReduced(false);
//        $ticket3->setReduced(true);
//
//        $ticket1->setBirthDate('1988-01-10');
//        $ticket2->setBirthDate('1968-05-05');
//        $ticket3->setBirthDate('2017-01-01');
//
//        $order->addTicket($ticket1);
//        $order->addTicket($ticket2);
//        $order->addTicket($ticket3);
//
//        //Ticket1 est réduit et la personne a plus de 12 ans -> 10€
//        //Ticket2 ne l'est pas et la personne se situe entre 12 et 60 ans -> 16€
//        //Ticket3 est réduit mais la personne a moins de 4 ans -> 0€
//        //total = 26€
//
//
//        $order->setVisitDate(new \DateTime());
//        $order->setHalfDay(false);
//
//
//        $price = new LouvrePriceCalculator();
//        $result = $price->setCommandPrice($order);
//
//        $this->assertSame(26, $result);
//
//    }


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