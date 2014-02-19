<?php

namespace Cbnv\MainBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class DefaultControllerTest extends WebTestCase
{
    public function testIndex()
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/admin');
        $this->assertTrue($client->getResponse()->isRedirect());
    }

    public function testLogin()
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/login');
        $this->assertTrue($client->getResponse()->isSuccessful());
        $this->assertGreaterThan(0, $crawler->filter('h1:contains("Admin")')->count());

        // Soumission du formulaire
        $form = $crawler->selectButton('_submit')->form();

        $crawler = $client->submit($form, array('_username' => 'admin', '_password' => 'adminpass'));
        var_dump($client->getResponse()->getContent());
        $this->assertTrue($client->getResponse()->isRedirect(	));
    }
}
