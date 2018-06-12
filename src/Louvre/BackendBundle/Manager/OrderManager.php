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
use Louvre\BackendBundle\Exception\InvalidOrderException;
use Louvre\BackendBundle\Utils\LouvreIdGenerator;
use Louvre\BackendBundle\Utils\LouvreMailSender;
use Louvre\BackendBundle\Utils\LouvrePriceCalculator;
use Louvre\BackendBundle\Utils\LouvreStripePaymentChecker;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Doctrine\ORM\EntityManagerInterface;



class OrderManager
{
    /**
     * @var Session
     */
    private $session;

    /**
     * @var RequestStack
     */
    private $requestStack;

    /**
     * @var EntityManagerInterface
     */
    private $em;
    /**
     * @var LouvreMailSender
     */
    private $louvreMailSender;

    /**
     * @var LouvrePriceCalculator
     */
    private $louvrePriceCalculator;
    /**
     * @var LouvreIdGenerator
     */
    private $louvreIdGenerator;
    /**
     * @var LouvreStripePaymentChecker
     */
    private $louvreStripePaymentChecker;


    public function __construct(EntityManagerInterface $em,
                                LouvreMailSender $louvreMailSender,
                                RequestStack $requestStack,
                                SessionInterface $session,
                                LouvrePriceCalculator $louvrePriceCalculator,
                                LouvreIdGenerator $louvreIdGenerator,
                                LouvreStripePaymentChecker $louvreStripePaymentChecker)
    {
        $this->session = $session;
        $this->requestStack = $requestStack;
        $this->em = $em;
        $this->louvreMailSender = $louvreMailSender;
        $this->louvrePriceCalculator = $louvrePriceCalculator;
        $this->louvreIdGenerator = $louvreIdGenerator;
        $this->louvreStripePaymentChecker = $louvreStripePaymentChecker;
    }

    /**
     * @param null $expectedStatus
     * @return Command
     * @throws InvalidOrderException
     */
    public function getOrder($expectedStatus = null)
    {
        /** @var Command $order */
        $order = $this->session->get('order');

        if (!$order) {
            throw new InvalidOrderException();
        }
        if ($expectedStatus && $order->getOrderStatut() !== $expectedStatus) {
            throw new InvalidOrderException();
        }

        return $order;

    }

    /**
     * @return Command
     */
    public function init()
    {
        try {
            $command = $this->getOrder();
        } catch (InvalidOrderException $e) {
            $command = new Command();
        }

        return $command;
    }


    /**
     * @param Command $order
     * @return mixed
     */
    public function initOrder(Command $order)
    {
        while($order->getTickets()->count() !== $order->getNbTickets())
        {
            if ($order->getNbTickets() > $order->getTickets()->count()) {
                $ticket = new Tickets();
                $order->addTicket($ticket);
            }
            if ($order->getNbTickets() < $order->getTickets()->count()) {
                $order->removeTicket($order->getTickets()->last());
            }
        }

        $order->setOrderStatut($order::COMMANDE_EN_ATTENTE);

        return $this->session->set(
            'order', $order
        );

    }


    /**
     * @param Command $order
     */
    public function validateOrder(Command $order)
    {
        $this->louvrePriceCalculator->setCommandPrice($order);
        $order->setOrderStatut($order::COMMANDE_EN_ATTENTE);

    }


    /**
     * @param Command $order
     * @return bool
     */
    public function paymentOrder(Command $order)
    {
//        $request = $this->requestStack->getCurrentRequest();


//        $token = $request->request->get('stripeToken');

//        $price = $this->session->get('order')->getPrice();

//        try {
//        \Stripe\Stripe::setApiKey($this->stripePrivateKey);
//        \Stripe\Charge::create(array(
//                "amount" => $price * 100,
//                "currency" => "eur",
//                "source" => $token,
//                "description" => "Paiement"
//            ));
//        } catch (\Stripe\Error\ApiConnection $e) {
//            $error = "error.stripe.communication";
//            $this->session->getFlashBag()->add('erreur', $error);
//            return false;
//        } catch (\Stripe\Error\InvalidRequest $e) {
//            $error = "error.stripe.interne";
//            $this->session->getFlashBag()->add('erreur', $error);
//            return false;
//        } catch (\Stripe\Error\Api $e) {
//            $error = "error.stripe.serveur";
//            $this->session->getFlashBag()->add('erreur', $error);
//            return false;
//        } catch (\Stripe\Error\Card $e) {
//            $e_json = $e->getJsonBody();
//            $error = $e_json['error'];
//            $this->session->getFlashBag()->add('erreur', $error);
//            return false;
//        }

        $uniqueId = $this->louvreIdGenerator->generateUniqueId();
        $mail = $this->session->get('order')->getMail();

        if ($this->louvreStripePaymentChecker->CheckPayment($order))
        {

        $order->setOrderStatut($order::PAIEMENT_VALIDE);
        $order->SetOrderId($uniqueId);

        $this->louvreMailSender->sendMessage($mail, $order);

        $entityManager = $this->em;
        $entityManager->persist($order);
        $entityManager->flush();
        }
        return true;
    }

    /**
     *
     */
    public function clearCurrentOrder()
    {
        $this->session->clear();
    }
}