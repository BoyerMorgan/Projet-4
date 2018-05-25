<?php
/**
 * Created by PhpStorm.
 * User: MEGAPOUPOULE
 * Date: 25/05/2018
 * Time: 10:32
 */

namespace Tests\Louvre\BackendBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class BackendControllerTest extends WebTestCase
{
    public function testHomepageIsUp()
    {
        $client = static::createClient();
        $client->request('GET', '/homepage');

        $this->assertSame(200, $client->getResponse()->getStatusCode());

        echo $client->getResponse()->getContent();

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
        //$form['louvre_backendbundle_command_order[halfDay]'] = 0;
        $form['louvre_backendbundle_command_order[mail]'] = "morganboyer@gmail.com";
        $form['louvre_backendbundle_command_order[nbTickets]'] = 1;

        $client->submit($form);
        $crawler = $client->followRedirect();

        $billetForm = $crawler->selectButton('Valider')->form();

        $values = $billetForm->getPhpValues();

        $values['billet']['tickets']['0']['name'] = "Boyer";
        $values['billet']['tickets']['0']['forename'] = "Morgan";
        $values['billet']['tickets']['0']['birthDate']['day'] = 8;
        $values['billet']['tickets']['0']['birthDate']['month'] = 10;
        $values['billet']['tickets']['0']['birthDate']['year'] = 1988;
        //$billetForm['billet[tickets][0][reduced]'] = 0;
        $values['billet']['tickets']['0']['country'] = "FR";
        $values['cgv'] = "on";

        $client->request($billetForm->getMethod(), $billetForm->getUri(), $values, $billetForm->getPhpFiles());

        $crawler = $client->followRedirect();


        $this->assertSame(1, $crawler->filter('html:contains("Prix total de la commande")')->count());

        //echo $client->getResponse()->getContent();
    }
}