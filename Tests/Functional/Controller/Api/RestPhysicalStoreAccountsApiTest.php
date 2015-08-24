<?php

namespace DemacMedia\Bundle\PhysicalStoreBundle\Tests\Functional\Controller\Api;

use Oro\Bundle\TestFrameworkBundle\Test\WebTestCase;

class RestPhysicalStoreAccountsApiTest extends WebTestCase
{
    protected function setUp()
    {
        $this->initClient(array(), $this->generateWsseAuthHeader());
        // $this->loadFixtures(['Oro\Bundle\CommentBundle\Tests\Functional\DataFixtures\LoadAccountsData']);
    }

    /**
     * @return array
     */
    public function testCgetAccounts()
    {
        $this->client->request(
            'GET',
            $this->getUrl('demacmedia_api_physicalstore_get_accounts')
        );
        $response = $this->client->getResponse();
        $this->assertEquals(200, $response->getStatusCode(), $response->getContent());
    }
}
