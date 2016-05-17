<?php

namespace TrelloBurndown\Manager;

use Trello\Model\Board;
use TrelloBurndown\Client\TrelloClient;
use Trello\Model\Cardlist;

/**
 * Class ListManager.
 */
class ListManager
{
    /**
     * @var \Trello\Client
     */
    private $client;

    /**
     * ListManager constructor.
     *
     * @param TrelloClient $client
     */
    public function __construct(TrelloClient $client)
    {
        $this->client = $client->getClient();
    }

    /**
     * @param string $listName
     * @param Board  $board
     *
     * @return Cardlist|void
     */
    public function getListFromBoard(String $listName, Board $board)
    {
        $lists = $this->client->api('board')->lists()->all($board->getId());

        foreach ($lists as $list) {
            if ($list['name'] == $listName) {
                return new Cardlist($this->client, $list['id']);
            }
        }

        return;
    }

    /**
     * @param string $listName
     * @param array  $boards
     *
     * @return Cardlist|void
     *
     * @throws \Exception
     */
    public function getList(String $listName, array $boards)
    {
        $lists = [];
        foreach ($boards as $board) {
            if (!($board instanceof Board)) {
                throw new \Exception('Function ListManager::getList expect an array of Board as second argument');
            }

            $lists = array_merge($lists, $this->client->api('board')->lists()->all($board->getId()));
        }

        foreach ($lists as $list) {
            if ($list['name'] == $listName) {
                return new Cardlist($this->client, $list['id']);
            }
        }

        return;
    }
}
