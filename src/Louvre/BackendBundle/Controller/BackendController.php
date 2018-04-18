<?php

namespace Louvre\BackendBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class BackendController extends Controller
{
    public function indexAction()
    {
        return $this->render('LouvreBackendBundle:Backend:index.html.twig');
    }

    public function commandeAction()
    {
        return $this->render('LouvreBackendBundle:Backend:commande.html.twig');
    }

    public function billetsAction()
    {
        return $this->render('LouvreBackendBundle:Backend:billets.html.twig');

    }

    public function contactAction()
    {
        return $this->render('LouvreBackendBundle:Backend:contact.html.twig');
    }
}
