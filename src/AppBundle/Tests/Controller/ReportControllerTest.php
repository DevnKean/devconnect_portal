<?php

namespace AppBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class ReportControllerTest extends WebTestCase
{
    public function testLeadtracker()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/lead-tracker');
    }

}
