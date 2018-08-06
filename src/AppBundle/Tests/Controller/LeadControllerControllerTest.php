<?php

namespace AppBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class LeadControllerControllerTest extends WebTestCase
{
    public function testAllocate()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/allocate');
    }

    public function testApprove()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/approve');
    }

}
