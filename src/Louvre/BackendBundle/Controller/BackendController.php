<?php

namespace Louvre\BackendBundle\Controller;

use Louvre\BackendBundle\Entity\Command;
use Louvre\BackendBundle\Entity\Tickets;
use Louvre\BackendBundle\Form\BilletType;
use Louvre\BackendBundle\Form\CommandType;
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
        $session = $this->get('session');

        $order = new Command();
        $form = $this->get('form.factory')->create(CommandType::class, $order);
        $form->handleRequest($request);

        if ($request->isMethod('POST') && $form->isValid()) {

            //Création du nombre de Tickets demandé par l'utilisateur
            for ($i = 1; $i <= $order->getNbTickets(); $i++) {
                $ticket = new Tickets();
                $order->addTicket($ticket);
            }

            $session->set(
                'order', $order
            );

            return $this->redirectToRoute('louvre_backend_billets');
        }

        return $this->render('LouvreBackendBundle:Backend:commande.html.twig', array(
            'form' => $form->createView(),
        ));
    }

    public function billetsAction (Request $request)
    {
        $order = $this->get('session')->get('order');
        $form = $this->get('form.factory')->create(BilletType::class, $order);
        $form->handleRequest($request);

        if ($request->isMethod('POST') && $form->isValid()) {

            $calculator = $this->container->get('louvre_backend.pricecalculator');
            $priceTotal = $calculator->commandPrice($order);
            $order->setPrice($priceTotal);

            return $this->redirectToRoute('louvre_backend_confirmation');
        }

        return $this->render('LouvreBackendBundle:Backend:billets.html.twig', array(
            'form' => $form->createView(),

        ));
    }

    public function confirmationAction(Request $request)
    {
        if ($request->isMethod('POST'))
        {
            $order = $this->get('session')->get('order');
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($order);
            $entityManager->flush();

            return $this->redirectToRoute('louvre_backend_email');
        }
        return $this->render('LouvreBackendBundle:Backend:confirmation.html.twig',[
            'order' => $request->getSession()->get('order')
        ]);
    }

    public function emailAction(Request $request)
    {
        return $this->render('LouvreBackendBundle:Backend:email.html.twig');
    }

    public function contactAction()
    {
        return $this->render('LouvreBackendBundle:Backend:contact.html.twig');
    }

    public function cgvAction()
    {
        return $this->render('LouvreBackendBundle:Backend:cgv.html.twig');
    }
}
