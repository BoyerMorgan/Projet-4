<?php
/**
 * Created by PhpStorm.
 * User: MEGAPOUPOULE
 * Date: 28/05/2018
 * Time: 12:49
 */

namespace Louvre\BackendBundle\Exception;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Router;


class InvalidOrderException extends \Exception
{
    /**
     * @var SessionInterface
     */
    private $session;
    /**
     * @var Router
     */
    private $router;

    public function __construct(SessionInterface $session, Router $router)
    {
        $this->session = $session;
        $this->router = $router;
    }

    public function processError($message)
    {
        $this->session->getFlashBag()->add('erreur', $message);
        $this->router->generate('index');

    }
}