<?php

namespace DemacMedia\Bundle\PhysicalStoreBundle\Tests\Functional\Controller;

use Symfony\Component\DomCrawler\Crawler;
use Oro\Bundle\TestFrameworkBundle\Test\WebTestCase;
use DemacMedia\Bundle\PhysicalStoreBundle\Controller\PhysicalStoreAccountsController;

class PhysicalStoreAccountsControllerTest extends WebTestCase
{
    public function testIndex()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/hello/Fabien');

        $this->assertTrue($crawler->filter('html:contains("Hello Fabien")')->count() > 0);
    }
}
