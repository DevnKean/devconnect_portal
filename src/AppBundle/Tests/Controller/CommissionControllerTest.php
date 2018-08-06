<?php

namespace AppBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class CommissionControllerTest extends WebTestCase
{
    public function testList()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/list');
    }

    public function testModels()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/models');
    }

    public function testModelnew()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/model/new');
    }

}
