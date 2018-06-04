<?php
/**
 * Created by PhpStorm.
 * User: MEGAPOUPOULE
 * Date: 04/06/2018
 * Time: 11:22
 */

namespace Louvre\BackendBundle\Listener;

use Louvre\BackendBundle\Exception\InvalidOrderException;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpKernel\Event\GetResponseForExceptionEvent;
use Symfony\Component\Routing\Router;

class InvalidOrderExceptionListener
{
    /**
     * @var Router
     */
    private $router;
    /**
     * @var Session
     */
    private $session;

    public function __construct(Router $router, Session $session)
    {
        $this->router = $router;
        $this->session = $session;
    }

    public function onKernelException(GetResponseForExceptionEvent $event)
    {
        // You get the exception object from the received event
        $exception = $event->getException();

        // HttpExceptionInterface is a special type of exception that
        // holds status code and header details
        if ($exception instanceof InvalidOrderException) {

            $this->session->getFlashBag()->add('erreur', 'Vous n\'avez pas accès à cette page !');
            $response = new RedirectResponse($this->router->generate('index'));
            $event->setResponse($response);
        }

    }
}