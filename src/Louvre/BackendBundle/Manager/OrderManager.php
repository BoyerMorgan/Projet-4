<?php
/**
 * Created by PhpStorm.
 * User: MEGAPOUPOULE
 * Date: 14/05/2018
 * Time: 12:13
 */

namespace Louvre\BackendBundle\Manager;

use Louvre\BackendBundle\Entity\Tickets;
use Louvre\BackendBundle\Entity\Command;


use Symfony\Component\HttpFoundation\Session\SessionInterface;

class OrderManager
{
    /**
     * @var SessionInterface
     */
    private $session;
    private $mailer;
    private $templating;

    public function __construct(SessionInterface $session, \Swift_Mailer $mailer, \Twig_Environment $templating)
    {
        $this->session = $session;
        $this->mailer = $mailer;
        $this->templating = $templating;
    }


    public function init()
    {
        return $this->session;

    }

    public function getOrder()
    {
        return $this->session->get('order');
    }

    public function getOrderMail()
    {
        return $this->session->get('order')->getMail();
    }

    public function getOrderTickets()
    {
        return $this->session->get('order')->getTickets();
    }

    public function setData($data)
    {
        return $this->session->set(
            'order', $data
        );
    }

    public function CreateTickets(Command $order)
    {
        for ($i = 1; $i <= $order->getNbTickets(); $i++) {
            $ticket = new Tickets();
            $order->addTicket($ticket);
        }
    }

    public function SendMessage($mail, $order)
    {
        $message = (new \Swift_Message('Confirmation de votre commande'))
            ->setFrom('louvre@example.com')
            ->setTo($mail)
            ->setBody(
                $this->templating->render(
                    'Emails/emailconfirmation.html.twig', [
                        'order' => $order
                    ]
                ),
                'text/html'
            );

        $this->mailer->send($message);
    }

    function GenerateUniqueId() {
        $uniqueId = 0;
        srand((double) microtime(TRUE) * 1000000);
        $chars = array(
            'a', 'b', 'c', 'd', 'e', 'f', 'g', 'h', 'i', 'j', 'k', 'l', 'm', 'n', 'p',
            'q', 'r', 's', 't', 'u', 'v', 'w', 'x', 'y', 'z', '1', '2', '3', '4', '5',
            '6', '7', '8', '9', 'A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K',
            'L', 'M', 'N', 'O', 'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X', 'Y', 'Z');

        for ($rand = 0; $rand <= 20; $rand++) {
            $random = rand(0, count($chars) - 1);
            $uniqueId .= $chars[$random];
        }
        return $uniqueId;
    }

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