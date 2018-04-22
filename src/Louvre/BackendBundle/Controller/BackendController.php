<?php

namespace Louvre\BackendBundle\Controller;

use Louvre\BackendBundle\Entity\Command;
use Louvre\BackendBundle\Form\CommandType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;


class BackendController extends Controller
{
    public function indexAction()
    {
        return $this->render('LouvreBackendBundle:Backend:index.html.twig');
    }

    public function orderAction(Request $request)
    {
        $session = $this->get('session');

        $order = new Command();
        $form = $this->get('form.factory')->create(CommandType::class, $order);
        $form->handleRequest($request);

        if ($request->isMethod('POST') && $form->isValid()) {

            //Appel au service de calcul du prix total
            $calculator = $this->container->get('louvre_backend.pricecalculator');
            $price = $calculator->commandPrice($order);

            $order->setPrice($price);

            $session->set(
                'order', $order
            );
            return $this->redirectToRoute('louvre_backend_confirmation');

        }

        return $this->render('LouvreBackendBundle:Backend:commande.html.twig', array(
            'form' => $form->createView(),
        ));
    }

    public function confirmationAction()
    {
        return $this->render('LouvreBackendBundle:Backend:confirmation.html.twig');
    }

    public function contactAction()
    {
        return $this->render('LouvreBackendBundle:Backend:contact.html.twig');
    }
}
