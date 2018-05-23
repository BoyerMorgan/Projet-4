<?php

namespace Louvre\BackendBundle\Controller;

use Louvre\BackendBundle\Entity\Command;
use Louvre\BackendBundle\Form\BilletType;
use Louvre\BackendBundle\Form\CommandType;
use Louvre\BackendBundle\Manager\FormManager;
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
        return $this->render('default/index.html.twig');
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
        $order = $orderManager->init();

        $form = $this->get('form.factory')->create(CommandType::class, $order);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $orderManager->initOrder($order);

            return $this->redirectToRoute('billets');
        }

        return $this->render('default/commande.html.twig', array(
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
    public function billetsAction(Request $request, OrderManager $orderManager)
    {
        $order = $orderManager->getOrder();

        if ($orderManager->getOrder()->getOrderStatut() === "Commande_en_attente") {
            $form = $this->get('form.factory')->create(BilletType::class, $order);
            $form->handleRequest($request);
        }
        else throw $this->createNotFoundException('La page demandée n\'est pas valide');

        if ($form->isSubmitted() && $form->isValid()) {

            $orderManager->SetCommandPrice($order);

            return $this->redirectToRoute('recap');
        }

        return $this->render('default/billets.html.twig', array(
            'form' => $form->createView(),

        ));
    }

    /**
     * @param Request $request
     * @param OrderManager $orderManager
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     *
     * @Route("/commande/recap", name="recap")
     */
    public function RecapAction(Request $request, OrderManager $orderManager)
    {
        if ($orderManager->getOrder()->getOrderStatut() !== "Commande_en_attente_de_paiement") {
            throw $this->createNotFoundException('La page demandée n\'est pas valide');
        }

        if ($request->isMethod('POST')) {

            $order = $orderManager->getOrder();
            $price = $orderManager->getOrder()->getPrice();

            $orderManager->validateOrder($price, $order);


            return $this->redirectToRoute('confirmation');
        }
        return $this->render('default/recap.html.twig', [
            'order' => $orderManager->getOrder(),
        ]);
    }

    /**
     * @param OrderManager $orderManager
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @Route ("/commande/confirmation", name="confirmation")
     */
    public function confirmationAction(OrderManager $orderManager)
    {
        if($orderManager->getOrder()->getOrderStatut() !== "Paiement_valide") {
            throw $this->createNotFoundException('La page demandée n\'est pas valide');
        }
        $order = $orderManager->getOrder();
        $mail = $orderManager->getOrder()->getMail();
        $price = $orderManager->getOrder()->getPrice();
        $orderId = $orderManager->getOrder()->getOrderId();

        $orderManager->sendMessage($mail, $order);
        $orderManager->clearSession();

        return $this->render('default/confirmation.html.twig', [
            'mail' => $mail,
            'price' => $price,
            'orderId' => $orderId,

        ]);


    }

    /**
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @Route("/contact", name="contact")
     */
    public function contactAction()
    {
        return $this->render('default/contact.html.twig');
    }

    /**
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @Route("/cgv", name="cgv")
     */
    public function cgvAction()
    {
        return $this->render('default/cgv.html.twig');
    }
}
