<?php

namespace TrelloBurndown\Client;

use Trello\Client;

/**
 * Class TrelloClient.
 */
class TrelloClient
{
    /**
     * @var string
     */
    private $apiKey;
    /**
     * @var string
     */
    private $apiToken;
    /**
     * @var Client
     */
    protected $client;

    /**
     * TrelloManager constructor.
     *
     * @param string $apiKey
     * @param string $apiToken
     */
    public function __construct(String $apiKey, String $apiToken)
    {
        $this->apiKey = $apiKey;
        $this->apiToken = $apiToken;
        $this->setClient();
    }

    /**
     * @param string $apiKey
     */
    public function setApiKey(String $apiKey)
    {
        $this->apiKey = $apiKey;
    }

    /**
     * @param string $apiToken
     */
    public function setApiToken(String $apiToken)
    {
        $this->apiToken = $apiToken;
    }

    /**
     * @return string
     */
    public function getApiKey()
    {
        return $this->apiKey;
    }

    /**
     * @return string
     */
    public function getApiToken()
    {
        return $this->apiToken;
    }

    /**
     * Set Client and authenticate.
     */
    public function setClient()
    {
        $this->client = new Client();
        $this->client->authenticate($this->apiKey, $this->apiToken, Client::AUTH_URL_CLIENT_ID);
    }

    /**
     * @return Client
     */
    public function getClient()
    {
        return $this->client;
    }
}
