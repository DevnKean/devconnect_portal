<?php

namespace SupplierBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class LogControllerTest extends WebTestCase
{
    public function testProfilelog()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/profileLog');
    }

}
