<?php

namespace Louvre\BackendBundle\Controller;

use Louvre\BackendBundle\Entity\Command;
use Louvre\BackendBundle\Entity\Tickets;
use Louvre\BackendBundle\Form\BilletType;
use Louvre\BackendBundle\Form\CommandType;
use Louvre\BackendBundle\Manager\OrderManager;
use Louvre\BackendBundle\PriceCalculator\LouvrePricecalculator;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;



class BackendController extends Controller
{
    /**
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @Route("/", name="homepage")
     */
    public function indexAction()
    {
        return $this->render('LouvreBackendBundle:Backend:index.html.twig');
    }

    public function orderAction(Request $request, OrderManager $orderManager)
    {
       $orderManager->init();

        $order = new Command();
        $form = $this->createForm(CommandType::class, $order);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){

           $orderManager->CreateTickets($order);
           $orderManager->setData($order);

            return $this->redirectToRoute('louvre_backend_billets');
        }

        return $this->render('LouvreBackendBundle:Backend:commande.html.twig', array(
            'form' => $form->createView(),
        ));
    }

    public function billetsAction (Request $request, OrderManager $orderManager)
    {
        $order = $orderManager->getOrder();
        $form = $this->get('form.factory')->create(BilletType::class, $order);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $priceTotal = $orderManager->commandPrice($order);
            $uniqueId = $orderManager->GenerateUniqueId();

            $order->SetOrderId($uniqueId);
            $order->setPrice($priceTotal);

            return $this->redirectToRoute('louvre_backend_confirmation');
        }

        return $this->render('LouvreBackendBundle:Backend:billets.html.twig', array(
            'form' => $form->createView(),

        ));
    }

    public function confirmationAction(Request $request, OrderManager $orderManager)
    {
        if ($request->isMethod('POST'))
        {
            $order = $orderManager->getOrder();
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($order);
            $entityManager->flush();

            return $this->redirectToRoute('louvre_backend_email');
        }
        return $this->render('LouvreBackendBundle:Backend:confirmation.html.twig',[
            'order' => $orderManager->getOrder()
        ]);
    }

    public function emailAction(Request $request, OrderManager $orderManager)
    {
        $mail = $orderManager->getOrderMail();
        $order = $orderManager->getOrder();
        $orderManager->SendMessage($mail, $order);

        return $this->render('LouvreBackendBundle:Backend:email.html.twig',[
            'order' => $orderManager->getOrder()
        ]);
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
