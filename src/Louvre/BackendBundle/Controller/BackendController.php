<?php

namespace Louvre\BackendBundle\Controller;

use Louvre\BackendBundle\Entity\Command;
use Louvre\BackendBundle\Entity\Tickets;
use Louvre\BackendBundle\Form\CommandType;
use Louvre\BackendBundle\Form\TicketsType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;


class BackendController extends Controller
{
    public function indexAction()
    {
        return $this->render('LouvreBackendBundle:Backend:index.html.twig');
    }

    public function orderAction(Request $request)
    {
        $order = new Command();

        $form = $this->get('form.factory')->create(CommandType::class, $order);

        if ($request->isMethod('POST')) {

            $form->handleRequest($request);

            if ($form->isValid()) {

               /* $session = $request->getSession();
                $session->set(
                    'mail', $order->getMail(),
                    'visitDate', $order->getVisitDate(),
                    'halfDay', $order->getHalfDay(),
                    'nbTickets', $order->getNbTickets()
                );*/


                return $this->redirectToRoute('louvre_backend_confirmation');
            }
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
