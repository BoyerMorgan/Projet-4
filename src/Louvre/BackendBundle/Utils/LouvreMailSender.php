<?php

namespace Louvre\BackendBundle\Utils;

class LouvreMailSender
{
    private $mailer;
    private $templating;

    public function __construct(\Swift_Mailer $mailer, \Twig_Environment $templating)
    {
        $this->mailer = $mailer;
        $this->templating = $templating;
    }

    public function sendMessage($mail, $order)
    {
        $message = (new \Swift_Message('Confirmation de votre commande'))
            ->setFrom('louvre@example.com')
            ->setTo($mail)
            ->setBody(
                $this->templating->render(
                    'Emails/emailconfirmation.html.twig', [
                        'order' => $order
                    ]
                ),
                'text/html'
            );

        $this->mailer->send($message);


    }
}