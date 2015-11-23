<?php

namespace DemacMedia\Bundle\PhysicalStoreBundle\Tests\Functional\Controller\Api\Rest;

use Oro\Bundle\TestFrameworkBundle\Test\WebTestCase;

/**
 * @outputBuffering enabled
 * @dbReindex
 * @dbIsolation
 */
class PhysicalStoreRestAccountsControllerTest extends WebTestCase
{
    protected function setUp()
    {
        $this->initClient(['debug' => false], $this->generateWsseAuthHeader());
    }

    public function testgetAction()
    {
        $accountId = 6;

        $this->client->request(
            'POST',
            $this->getUrl('demacmedia_physicalstore_accounts_view', ['id' => $accountId])
        );

        $response = $this->client->getResponse();

        $this->assertResponseStatusCodeEquals($response, 200);
    }

}
