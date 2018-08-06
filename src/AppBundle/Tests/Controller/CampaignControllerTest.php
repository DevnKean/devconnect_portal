<?php

namespace AppBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class CampaignControllerTest extends WebTestCase
{
    public function testAll()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/all');
    }

}
