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
     * @Route("/", name="index")
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

        $form = $this->createForm(CommandType::class, $order);
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
     * @throws
     * @Route("/commande/billets", name="billets")
     */
    public function billetsAction(Request $request, OrderManager $orderManager)
    {
        $order = $orderManager->getOrder(Command::COMMANDE_EN_ATTENTE);

        $form = $this->createForm(BilletType::class, $order);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $orderManager->validateOrder($order);

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
     * @throws \Doctrine\ORM\OptimisticLockException
     *
     * @Route("/commande/recap", name="recap")
     * @throws \Louvre\BackendBundle\Exception\InvalidOrderException
     */
    public function recapAction(Request $request, OrderManager $orderManager)
    {
        $order = $orderManager->getOrder(Command::COMMANDE_EN_ATTENTE);

        if ($request->isMethod('POST')) {

            if($orderManager->paymentOrder($order)){
                return $this->redirectToRoute('confirmation');
            }

        }
        return $this->render('default/recap.html.twig', [
            'order' => $order,
        ]);
    }

    /**
     * @param OrderManager $orderManager
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @Route ("/commande/confirmation", name="confirmation")
     * @throws \Louvre\BackendBundle\Exception\InvalidOrderException
     */
    public function confirmationAction(OrderManager $orderManager)
    {

        $order = $orderManager->getOrder(Command::PAIEMENT_VALIDE);

        $orderManager->confirmationOrder();

        return $this->render('default/confirmation.html.twig', [
         'order' => $order
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
