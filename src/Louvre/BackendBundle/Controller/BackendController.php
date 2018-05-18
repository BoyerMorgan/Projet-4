<?php

namespace Louvre\BackendBundle\Controller;

use Louvre\BackendBundle\Entity\Command;
use Louvre\BackendBundle\Form\BilletType;
use Louvre\BackendBundle\Form\CommandType;
use Louvre\BackendBundle\Manager\OrderManager;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;



class BackendController extends Controller
{
    /**
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @Route("/homepage", name="index")
     */
    public function indexAction()
    {
        return $this->render('LouvreBackendBundle:Backend:index.html.twig');
    }

    /**
     * @param Request $request
     * @param OrderManager $orderManager
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     *
     * @Route("/commande", name="commande")
     */
    public function commandeAction(Request $request, OrderManager $orderManager)
    {
       $orderManager->init();


           $order = new Command();
           $form = $this->get('form.factory')->create(CommandType::class, $order);
           $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){

           $orderManager->CreateTickets($order);
           $orderManager->setData($order);

            return $this->redirectToRoute('billets');
        }

        return $this->render('LouvreBackendBundle:Backend:commande.html.twig', array(
            'form' => $form->createView(),
        ));
    }

    /**
     * @param Request $request
     * @param OrderManager $orderManager
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     *
     * @Route("/commande/billets", name="billets")
     */
    public function billetsAction (Request $request, OrderManager $orderManager)
    {
        $order = $orderManager->getOrder();
        $tickets = $orderManager->getOrderTickets();

        if (empty($ticket)) {
            $form = $this->get('form.factory')->create(BilletType::class, $order);
            $form->handleRequest($request);
        }
        else {
            $form = $this->get('form.factory')->create(BilletType::class, $tickets);
        }

            if ($form->isSubmitted() && $form->isValid()) {

                $priceTotal = $orderManager->commandPrice($order);
                $uniqueId = $orderManager->GenerateUniqueId();

                $order->SetOrderId($uniqueId);
                $order->setPrice($priceTotal);

                return $this->redirectToRoute('confirmation');
            }

        return $this->render('LouvreBackendBundle:Backend:billets.html.twig', array(
            'form' => $form->createView(),

        ));
    }

    /**
     * @param Request $request
     * @param OrderManager $orderManager
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     *
     * @Route("/commande/confirmation", name="confirmation")
     */
    public function confirmationAction(Request $request, OrderManager $orderManager)
    {
        if ($request->isMethod('POST'))
        {
            $order = $orderManager->getOrder();
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($order);
            $entityManager->flush();

            return $this->redirectToRoute('email');
        }
        return $this->render('LouvreBackendBundle:Backend:confirmation.html.twig',[
            'order' => $orderManager->getOrder()
        ]);
    }

    /**
     * @param OrderManager $orderManager
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @Route ("/commande/email", name="email")
     */
    public function emailAction(OrderManager $orderManager)
    {
        $mail = $orderManager->getOrderMail();
        $order = $orderManager->getOrder();
        $orderManager->SendMessage($mail, $order);

        return $this->render('LouvreBackendBundle:Backend:email.html.twig',[
            'order' => $orderManager->getOrder()
        ]);
    }

    /**
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @Route("/contact", name="contact")
     */
    public function contactAction()
    {
        return $this->render('LouvreBackendBundle:Backend:contact.html.twig');
    }

    /**
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @Route("/cgv", name="cgv")
     */
    public function cgvAction()
    {
        return $this->render('LouvreBackendBundle:Backend:cgv.html.twig');
    }
}
