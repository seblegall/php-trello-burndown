<?php

namespace TrelloBurndown;

use Trello\Model\Board;
use Trello\Model\Cardlist;
use TrelloBurndown\Client\TrelloClient;
use TrelloBurndown\Manager\BoardManager;
use TrelloBurndown\Manager\ListManager;
use TrelloBurndown\Model\Sprint;

/**
 * Class BurndownGenerator.
 */
class BurndownGenerator
{
    /**
     * @var TrelloClient
     */
    private $client;

    /**
     * @var BoardManager
     */
    private $boardManager;

    /**
     * @var ListManager
     */
    private $listManager;

    /**
     * @var array
     */
    private $boards = [];

    /**
     * @var array
     */
    private $todoLists = [];

    /**
     * @var array
     */
    private $wipLists = [];

    /**
     * @var array
     */
    private $doneLists = [];

    /**
     * BurndownGenerator constructor.
     *
     * @param TrelloClient $client
     */
    public function __construct(TrelloClient $client)
    {
        $this->client = $client;
        $this->boardManager = new BoardManager($this->client);
        $this->listManager = new ListManager($this->client);
    }

    /**
     * @param string $boardName
     *
     * @throws \Exception
     */
    public function addBoard(String $boardName)
    {
        $board = $this->boardManager->getBoard($boardName);
        if ($board instanceof Board) {
            $this->boards[] = $board;
        }
    }

    /**
     * @param array  $lists
     * @param string $listName
     * @param $boardName
     *
     * @throws \Exception
     */
    private function addList(array &$lists, String $listName, $boardName)
    {
        if ($boardName !== null) {
            $board = $this->boardManager->getBoard($boardName);
            $list = $this->listManager->getListFromBoard($listName, $board);
            if (!is_null($list) && $list instanceof Cardlist) {
                $lists[] = $list;
            }
        } else {
            $list = $this->listManager->getList($listName, $this->boards);
            if (!is_null($list) && $list instanceof Cardlist) {
                $lists[] = $list;
            }
        }
    }

    /**
     * @param string      $listName
     * @param string|null $boardName
     */
    public function addTodoList(String $listName, String $boardName = null)
    {
        $this->addList($this->todoLists, $listName, $boardName);
    }

    /**
     * @param string      $listName
     * @param string|null $boardName
     */
    public function addWipList(String $listName, String $boardName = null)
    {
        $this->addList($this->wipLists, $listName, $boardName);
    }

    /**
     * @param string      $listName
     * @param string|null $boardName
     */
    public function addDoneList(String $listName, String $boardName = null)
    {
        $this->addList($this->doneLists, $listName, $boardName);
    }

    /**
     * @return array
     */
    public function getBoards()
    {
        return $this->boards;
    }

    /**
     * @return array
     */
    public function getTodoLists()
    {
        return $this->todoLists;
    }

    /**
     * @return array
     */
    public function getWipLists()
    {
        return $this->wipLists;
    }

    /**
     * @return array
     */
    public function getDoneLists()
    {
        return $this->doneLists;
    }

    /**
     * @param Sprint $sprint
     */
    public function generate(Sprint $sprint)
    {
        return;
    }
}
