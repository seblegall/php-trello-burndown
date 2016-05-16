<?php

namespace TrelloBurndown\Manager;

use TrelloBurndown\Client\TrelloClient;
use Trello\Manager;

/**
 * Class BoardManager.
 */
class BoardManager
{
    /**
     * @var \Trello\Client
     */
    protected $client;

    /**
     * BoardManager constructor.
     *
     * @param TrelloClient $trelloClient
     */
    public function __construct(TrelloClient $trelloClient)
    {
        $this->client = $trelloClient->getClient();
    }

    /**
     * Return in instance of Board get from the board name.
     *
     * @param string $boardName
     *
     * @return \Trello\Model\Board;
     *
     * @throws \Exception
     */
    public function getBoard(String $boardName)
    {
        $boards = $this->client->api('member')->boards()->all('me');
        foreach ($boards as $board) {
            if ($board['name'] == $boardName) {
                $boardId = $board['id'];
            }
        }

        $manager = new Manager($this->client);
        if (!isset($boardId)) {
            throw new \Exception("Board $boardName not found");
        }

        return $manager->getBoard($boardId);
    }
}
