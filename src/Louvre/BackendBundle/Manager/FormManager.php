<?php
/**
 * Created by PhpStorm.
 * User: MEGAPOUPOULE
 * Date: 19/05/2018
 * Time: 12:20
 */

namespace Louvre\BackendBundle\Manager;

use Louvre\BackendBundle\Form\BilletType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Louvre\BackendBundle\Form\CommandType;

class FormManager extends controller
{

    public function createCommandForm($form)
    {
        return $this->get('form.factory')->create(CommandType::class, $form);
    }

    public function createBilletsForm($form)
    {
        return $this->get('form.factory')->create(BilletType::class, $form);
    }

    public function insertIntoDb($order)
    {
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($order);
        $entityManager->flush();

    }
}
