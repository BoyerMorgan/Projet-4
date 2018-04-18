<?php

namespace Louvre\BackendBundle\Controller;

use Louvre\BackendBundle\Entity\Command_order;
use Louvre\BackendBundle\Form\Command_orderType;
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
        $order = new Command_order();

        $form = $this->get('form.factory')->create(Command_orderType::class, $order);

        if ($request->isMethod('POST')) {

            $form->handleRequest($request);

            if ($form->isValid()) {

                $em = $this->getDoctrine()->getManager();
                $em->persist($order);

                return $this->redirectToRoute('louvre_backend_billets');
            }
        }

        return $this->render('LouvreBackendBundle:Backend:commande.html.twig', array(
            'form' => $form->createView(),
        ));
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
