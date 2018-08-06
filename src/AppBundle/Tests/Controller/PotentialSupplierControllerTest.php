<?php

namespace AppBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class PotentialSupplierControllerTest extends WebTestCase
{
    public function testAll()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/all');
    }

    public function testEdit()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/edit');
    }

}
