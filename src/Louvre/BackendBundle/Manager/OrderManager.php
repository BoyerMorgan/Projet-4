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
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\DependencyInjection\ContainerInterface as Container;
use Doctrine\ORM\EntityManager;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class OrderManager
{
    /**
     * @var SessionInterface
     */
    private $session;
    private $mailer;
    private $templating;
    private $requestStack;
    private $container;
    private $em;

    public function __construct(EntityManager $em, Container $container, RequestStack $requestStack, SessionInterface $session, \Swift_Mailer $mailer, \Twig_Environment $templating)
    {
        $this->session = $session;
        $this->mailer = $mailer;
        $this->templating = $templating;
        $this->requestStack = $requestStack;
        $this->container = $container;
        $this->em = $em;
    }

    /**
     * @return Command
     */
    public function init()
    {
//        $command = $this->getOrder();
//
//        if (!$command) {
//            $command = new Command();
//            $this->setData($command);
//
//        }
        return new Command();

    }

    public function getOrder()
    {
        return $this->session->get('order');
    }

//    public function getOrderMail()
//    {
//        return $this->session->get('order')->getMail();
//    }
//
//    public function getOrderTickets()
//    {
//        return $this->session->get('order')->getTickets();
//    }

    public function setData($data)
    {
        return $this->session->set(
            'order', $data
        );
    }

    public function createTickets(Command $order)
    {
//        while ($order->getTickets()->count() != $order->getNbTickets()){
//            if($order->getTickets()->count() > $order->getNbTickets()){
//                $order->getTickets()->remove($order->getTickets()->last());
//            };
//            if($order->getTickets()->count() < $order->getNbTickets()){
//                $ticket = new Tickets();
//                $order->addTicket($ticket);
//            }
//        }

        for ($i = 1; $i <= $order->getNbTickets(); $i++) {
            $ticket = new Tickets();
            $order->addTicket($ticket);
        }
    }

    public function charge($price)
    {
        $request = $this->requestStack->getCurrentRequest();

        $token = $request->request->get('stripeToken');

        \Stripe\Stripe::setApiKey($this->container->getParameter('stripe_private_key'));
        \Stripe\Charge::create(array(
            "amount" => $price * 100 ,
            "currency" => "eur",
            "source" => $token,
            "description" => "Paiement"
        ));
    }

    public function ValidateCommand($order)
    {
        $entityManager = $this->em;
        $entityManager->persist($order);
        $entityManager->flush();
    }


    public function sendMessage($mail, $order)
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

    function GenerateUniqueId()
    {
        $uniqueId = 0;
        srand((double)microtime(TRUE) * 1000000);
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
     * @param Command $command
     * @return int
     */
    public function SetCommandPrice(Command $command)
    {
        $total = 0;
        $dateActual = $command->getVisitDate();
        $tickets = $command->getTickets();

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

        $command->setPrice($total);

        return $total;

    }
}