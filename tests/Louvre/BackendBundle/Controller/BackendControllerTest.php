<?php
/**
 * Created by PhpStorm.
 * User: MEGAPOUPOULE
 * Date: 25/05/2018
 * Time: 10:32
 */

namespace Tests\Louvre\BackendBundle\Controller;

use Louvre\BackendBundle\Entity\Command;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class BackendControllerTest extends WebTestCase
{
    /**
     * @dataProvider urlProvider
     * @param $status
     * @param $url
     * @param Command $order
     */
    public function testPageIsSuccessful($status, $url,Command $order = null)
    {
        $client = self::createClient();
        $session = $client->getContainer()->get('session');

        if($order){
            $session->set('order', $order);
        }

        $client->request('GET', $url);

        $this->assertSame($status,$client->getResponse()->getStatusCode());
    }


    public function urlProvider()
    {

       $order = new Command();
        yield [200,'/homepage'];
        yield [200,'/commande'];
        yield [404,'/commande/billets'];
        yield [200,'/commande/billets', clone $order->setOrderStatut(Command::COMMANDE_EN_ATTENTE)];
        yield [404,'/commande/recap'];
        yield [200,'/commande/recap', clone $order->setOrderStatut(Command::COMMANDE_EN_ATTENTE)];
        yield [404,'/commande/confirmation'];
        yield [200,'/commande/confirmation', clone $order->setOrderStatut(Command::PAIEMENT_VALIDE)];
        yield [200,'/contact'];
        yield [200,'/cgv'];
    }


    public function testAddNewOrder()
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/homepage');

        $link = $crawler->selectLink('Accéder à la Billetterie')->link();
        $crawler = $client->click($link);

        $form = $crawler->selectButton('Valider la commande')->form();

        $form['louvre_backendbundle_command_order[visitDate][day]'] = 25;
        $form['louvre_backendbundle_command_order[visitDate][month]'] = 8;
        $form['louvre_backendbundle_command_order[visitDate][year]'] = 2018;
        $form['louvre_backendbundle_command_order[mail]'] = "morganboyer@gmail.com";
        $form['louvre_backendbundle_command_order[nbTickets]'] = 2;

        $client->submit($form);
        $crawler = $client->followRedirect();

        $billetForm = $crawler->selectButton('Valider')->form();

        $values = $billetForm->getPhpValues();
        $values['billet']['tickets']['0']['name'] = "Boyer";
        $values['billet']['tickets']['0']['forename'] = "Morgan";
        $values['billet']['tickets']['0']['birthDate']['day'] = 8;
        $values['billet']['tickets']['0']['birthDate']['month'] = 10;
        $values['billet']['tickets']['0']['birthDate']['year'] = 1988;
        $values['billet']['tickets']['0']['country'] = "FR";

        $values['billet']['tickets']['1']['name'] = "Barat";
        $values['billet']['tickets']['1']['forename'] = "Ellie";
        $values['billet']['tickets']['1']['birthDate']['day'] = 8;
        $values['billet']['tickets']['1']['birthDate']['month'] = 10;
        $values['billet']['tickets']['1']['birthDate']['year'] = 1968;
        $values['billet']['tickets']['1']['country'] = "FR";

        $values['cgv'] = "on";

        $client->request($billetForm->getMethod(), $billetForm->getUri(), $values, $billetForm->getPhpFiles());

        $crawler = $client->followRedirect();


        $this->assertSame(1, $crawler->filter('html:contains("Prix total de la commande")')->count());

    }
}