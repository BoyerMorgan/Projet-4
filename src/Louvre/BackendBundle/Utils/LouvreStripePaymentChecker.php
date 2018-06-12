<?php

namespace Louvre\BackendBundle\Utils;

use Louvre\BackendBundle\Entity\Command;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class LouvreStripePaymentChecker
{
    /**
     * @var RequestStack
     */
    private $requestStack;
    /**
     * @var SessionInterface
     */
    private $session;
    /**
     * @var string
     */
    private $stripePrivateKey;

    public function __construct(RequestStack $requestStack,
                                SessionInterface $session,
                                $stripePrivateKey)
    {
        $this->requestStack = $requestStack;
        $this->session = $session;
        $this->stripePrivateKey = $stripePrivateKey;
    }


    public function CheckPayment(Command $order)
    {
        $request = $this->requestStack->getCurrentRequest();
        $token = $request->request->get('stripeToken');
        $price = $order->getPrice();

        try {
            \Stripe\Stripe::setApiKey($this->stripePrivateKey);
            \Stripe\Charge::create(array(
                "amount" => $price * 100,
                "currency" => "eur",
                "source" => $token,
                "description" => "Paiement"
            ));
        } catch (\Stripe\Error\ApiConnection $e) {
            $error = "error.stripe.communication";
            $this->session->getFlashBag()->add('erreur', $error);
            return false;
        } catch (\Stripe\Error\InvalidRequest $e) {
            $error = "error.stripe.interne";
            $this->session->getFlashBag()->add('erreur', $error);
            return false;
        } catch (\Stripe\Error\Api $e) {
            $error = "error.stripe.serveur";
            $this->session->getFlashBag()->add('erreur', $error);
            return false;
        } catch (\Stripe\Error\Card $e) {
            $e_json = $e->getJsonBody();
            $error = $e_json['error'];
            $this->session->getFlashBag()->add('erreur', $error);
            return false;
        }

        return true;
    }
}