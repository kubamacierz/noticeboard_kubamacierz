<?php

namespace AppBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class LayoutControllerControllerTest extends WebTestCase
{
    public function testShowmenu()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/menu');
    }

}
