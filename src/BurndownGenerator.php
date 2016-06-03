<?php

namespace TrelloBurndown;

use Trello\Model\Board;
use Trello\Model\Cardlist;
use TrelloBurndown\Client\TrelloClient;
use TrelloBurndown\Exception\TrelloItemNotFoundException;
use TrelloBurndown\Manager\ActionManager;
use TrelloBurndown\Manager\BoardManager;
use TrelloBurndown\Manager\ListManager;
use TrelloBurndown\Manager\StoryPointManager;
use TrelloBurndown\Model\Sprint;
use TrelloBurndown\Model\StoryPointBurndown;

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
     * @var ActionManager
     */
    private $actionManager;

    /**
     * @var StoryPointManager
     */
    private $storyPointManager;

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
        $this->actionManager = new ActionManager($this->client);
        $this->storyPointManager = new StoryPointManager($this->client, $this->actionManager);
    }

    /**
     * @param string $boardName
     *
     * @throws \Exception
     */
    public function addBoard(String $boardName)
    {
        $board = $this->boardManager->getBoard($boardName);
        if ($board ==  null || !$board instanceof Board) {
            throw new TrelloItemNotFoundException('board', $boardName);
        }
        $this->boards[] = $board;
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

            if ($board ==  null || !$board instanceof Board) {
                throw new TrelloItemNotFoundException('board', $boardName);
            }

            $list = $this->listManager->getListFromBoard($listName, $board);

            if ($list == null || !$list instanceof Cardlist) {
                throw new TrelloItemNotFoundException('list', $listName);
            }

            $lists[] = $list;
        } else {
            $list = $this->listManager->getList($listName, $this->boards);
            if ($list == null || !$list instanceof Cardlist) {
                throw new TrelloItemNotFoundException('list', $listName);
            }
            $lists[] = $list;
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
     *
     * @return StoryPointBurndown
     */
    public function getStoryPointBurndown(Sprint $sprint)
    {
        $doneSP = $this->storyPointManager->getDoneStoryPoints($this->todoLists, $this->wipLists, $this->doneLists, $sprint);
        $total = $this->storyPointManager->getTotalSprintStoryPoints($this->todoLists, $this->wipLists, $this->doneLists, $sprint);
        $average = $this->storyPointManager->getAverageStoryPointsPerDay($total, $sprint);

        $burndown = new StoryPointBurndown($sprint, $total, $doneSP, $average);

        return $burndown;
    }
}
