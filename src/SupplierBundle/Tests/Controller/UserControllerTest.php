<?php

namespace SupplierBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class UserControllerTest extends WebTestCase
{
    public function testAll()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/all');
    }

}
