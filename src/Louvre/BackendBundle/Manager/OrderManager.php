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
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Doctrine\ORM\EntityManager;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;


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
     * @var EntityManager
     */
    private $em;
    /**
     * @var LouvreMailSender
     */
    private $louvreMailSender;

    /**
     * @var string
     */
    private $stripePrivateKey;
    /**
     * @var LouvrePriceCalculator
     */
    private $louvrePriceCalculator;
    /**
     * @var LouvreIdGenerator
     */
    private $louvreIdGenerator;




    public function __construct(EntityManager $em,
                                LouvreMailSender $louvreMailSender,
                                RequestStack $requestStack,
                                SessionInterface $session,
                                $stripePrivateKey,
                                LouvrePriceCalculator $louvrePriceCalculator,
                                LouvreIdGenerator $louvreIdGenerator)
    {
        $this->session = $session;
        $this->requestStack = $requestStack;
        $this->em = $em;
        $this->louvreMailSender = $louvreMailSender;
        $this->stripePrivateKey = $stripePrivateKey;
        $this->louvrePriceCalculator = $louvrePriceCalculator;
        $this->louvreIdGenerator = $louvreIdGenerator;

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
        return new Command();
    }


    /**
     * @param Command $order
     * @return mixed
     */
    public function initOrder(Command $order)
    {

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
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function paymentOrder(Command $order)
    {
        $request = $this->requestStack->getCurrentRequest();
        $uniqueId = $this->louvreIdGenerator->generateUniqueId();

        $token = $request->request->get('stripeToken');

        $price = $this->session->get('order')->getPrice();
        $mail = $this->session->get('order')->getMail();


        try {
        \Stripe\Stripe::setApiKey($this->stripePrivateKey);
        \Stripe\Charge::create(array(
                "amount" => $price * 100,
                "currency" => "eur",
                "source" => $token,
                "description" => "Paiement"
            ));
        } catch (\Stripe\Error\ApiConnection $e) {
            $error = "Erreur de communication avec les serveurs de Stripe, veuillez rééssayer dans un instant";
            $this->session->getFlashBag()->add('erreur', $error);
            return false;
        } catch (\Stripe\Error\InvalidRequest $e) {
            $error = "Une erreur interne a été déclarée, celle-ci sera corrigée dans les plus brefs délais, veuillez nous excuser pour la gène occasionnée";
            $this->session->getFlashBag()->add('erreur', $error);
            return false;
        } catch (\Stripe\Error\Api $e) {
            $error = "Les serveurs de Stripe ne répondent pas, veuillez rééssayer dans un instant";
            $this->session->getFlashBag()->add('erreur', $error);
            return false;
        } catch (\Stripe\Error\Card $e) {
            $e_json = $e->getJsonBody();
            $error = $e_json['error'];
            $this->session->getFlashBag()->add('erreur', $error);
            return false;
        }

        $order->setOrderStatut($order::PAIEMENT_VALIDE);
        $order->SetOrderId($uniqueId);

        $this->louvreMailSender->sendMessage($mail, $order);

        $entityManager = $this->em;
        $entityManager->persist($order);
        $entityManager->flush();
        return true;
    }

    /**
     *
     */
    public function confirmationOrder()
    {
        $this->session->clear();
    }
}