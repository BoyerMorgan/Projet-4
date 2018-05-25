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

class OrderManager
{
    /**
     * @var SessionInterface
     */
    private $session;
    private $requestStack;
    private $container;
    private $em;

    public function __construct(EntityManager $em, Container $container, RequestStack $requestStack, SessionInterface $session)
    {
        $this->session = $session;
        $this->requestStack = $requestStack;
        $this->container = $container;
        $this->em = $em;
    }

    public function getOrder()
    {
        return $this->session->get('order');
    }

    /**
     * @return Command
     */
    public function init()
    {
        return new Command();
    }


    /**
     * @param Command $order
     * @return mixed
     */
    public function initOrder(Command $order)
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

        $order->setOrderStatut($order::COMMANDE_EN_ATTENTE);

        return $this->session->set(
            'order', $order
        );

    }

    /**
     * @param $price
     * @param Command $order
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function paymentOrder($price, Command $order)
    {
        $request = $this->requestStack->getCurrentRequest();

        $generator = $this->container->get("louvre_id.generator");
        $uniqueId = $generator->generateUniqueId();

        $token = $request->request->get('stripeToken');

        \Stripe\Stripe::setApiKey($this->container->getParameter('stripe_private_key'));
        \Stripe\Charge::create(array(
            "amount" => $price * 100 ,
            "currency" => "eur",
            "source" => $token,
            "description" => "Paiement"
        ));

        $order->setOrderStatut($order::PAIEMENT_VALIDE);
        $order->SetOrderId($uniqueId);

        $entityManager = $this->em;
        $entityManager->persist($order);
        $entityManager->flush();
    }

//    public function validateCommand(Command $order)
//    {
//        $uniqueId = $this->GenerateUniqueId();
//        $order->SetOrderId($uniqueId);
//
//        $entityManager = $this->em;
//        $entityManager->persist($order);
//        $entityManager->flush();
//    }


//
//
//    public function sendMessage($mail, $order)
//    {
//        $message = (new \Swift_Message('Confirmation de votre commande'))
//            ->setFrom('louvre@example.com')
//            ->setTo($mail)
//            ->setBody(
//                $this->templating->render(
//                    'Emails/emailconfirmation.html.twig', [
//                        'order' => $order
//                    ]
//                ),
//                'text/html'
//            );
//
//        $this->mailer->send($message);
//
//    }

//    function generateUniqueId()
//    {
//        $uniqueId = 0;
//        srand((double)microtime(TRUE) * 1000000);
//        $chars = array(
//            'a', 'b', 'c', 'd', 'e', 'f', 'g', 'h', 'i', 'j', 'k', 'l', 'm', 'n', 'p',
//            'q', 'r', 's', 't', 'u', 'v', 'w', 'x', 'y', 'z', '1', '2', '3', '4', '5',
//            '6', '7', '8', '9', 'A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K',
//            'L', 'M', 'N', 'O', 'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X', 'Y', 'Z');
//
//        for ($rand = 0; $rand <= 20; $rand++) {
//            $random = rand(0, count($chars) - 1);
//            $uniqueId .= $chars[$random];
//        }
//        return $uniqueId;
//    }

    /**
     * @param Command $order
     */
    public function ValidateOrder(Command $order)
    {
       $commandPrice = $this->container->get("louvre_price.calculator");
       $commandPrice->setCommandPrice($order);
       $order->setOrderStatut($order::COMMANDE_EN_ATTENTE);

    }

    /**
     * @param Command $order
     * @param $mail
     */
    public function ConfirmationOrder(Command $order, $mail)
    {
        $message = $this->container->get("louvre_mail.sender");
        $message->sendMessage($mail, $order);

        $this->session->clear();
    }
}