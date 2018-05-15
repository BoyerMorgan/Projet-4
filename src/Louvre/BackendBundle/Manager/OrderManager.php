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
use Symfony\Bundle\FrameworkBundle\Controller;


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
}