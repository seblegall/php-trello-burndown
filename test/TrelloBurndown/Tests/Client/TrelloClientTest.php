<?php

namespace TrelloBurndown\Tests\Client;

use Trello\Client;
use TrelloBurndown\Client\TrelloClient;

/**
 * Class TrelloClientTest
 */
class TrelloClientTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var string
     */
    private $apiKey = '547fdshjfsjh45677';

    /**
     * @var string
     */
    private  $apiToken = 'g4567ghjjlkh4567lk6567hjlk';

    /**
     * Test TrelloClient
     */
    public function testClient()
    {
        $trelloClient = new TrelloClient($this->apiKey, $this->apiToken);
        $this->assertInstanceOf(TrelloClient::class, $trelloClient);

        $this->assertInstanceOf(Client::class, $trelloClient->getClient());
    }

    /**
     * Test setting and getting api key and token
     */
    public function testSetApiKeyAndToken()
    {
        $trelloClient = new TrelloClient($this->apiKey, $this->apiToken);
        $trelloClient->setApiKey($this->apiKey."abc");
        $trelloClient->setApiToken($this->apiToken."def");

        $this->assertEquals($this->apiKey."abc", $trelloClient->getApiKey());
        $this->assertEquals($this->apiToken."def", $trelloClient->getApiToken());
    }


}