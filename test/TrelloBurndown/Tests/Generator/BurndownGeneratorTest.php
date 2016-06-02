<?php

namespace TrelloBurndown\Tests\Generator;

use Trello\Model\Board;
use Trello\Model\Cardlist;
use TrelloBurndown\BurndownGenerator;
use TrelloBurndown\Model\Sprint;
use TrelloBurndown\Tests\AbstractTestCase;

/**
 * Class BurndownGeneratorTest.
 */
class BurndownGeneratorTest extends AbstractTestCase
{
    /**
     * Test BurndownGenerator.
     */
    public function testBurndownGenerator()
    {
        $burndownGenerator = new BurndownGenerator($this->getTrelloClientMock());

        $this->assertInstanceOf(BurndownGenerator::class, $burndownGenerator);
    }

    /**
     * Test Adding Board.
     */
    public function testAddBoard()
    {
        $burndownGenerator = new BurndownGenerator($this->getTrelloClientMock());
        $bordName = $this->getBoardsData()[0]['name'];
        $burndownGenerator->addBoard($bordName);

        $this->assertInstanceOf(Board::class, $burndownGenerator->getBoards()[0]);
        $this->assertEquals($bordName, $burndownGenerator->getBoards()[0]->getName());
        $this->assertCount(1, $burndownGenerator->getBoards());

        $bordName = $this->getBoardsData()[1]['name'];
        $burndownGenerator->addBoard($bordName);

        $this->assertInstanceOf(Board::class, $burndownGenerator->getBoards()[1]);
        $this->assertCount(2, $burndownGenerator->getBoards());
    }

    /**
     * Test exception when board cannot be find.
     *
     * @expectedException \Exception
     * @expectedExceptionMessage Board test not found
     */
    public function testAddBoardWithWrongName()
    {
        $burndownGenerator = new BurndownGenerator($this->getTrelloClientMock());
        $bordName = 'test';
        $burndownGenerator->addBoard($bordName);
    }

    /**
     * Test Adding Todo List.
     */
    public function testAddTodoListWithoutBoard()
    {
        $burndownGenerator = new BurndownGenerator($this->getTrelloClientMock());
        $bordName = $this->getBoardsData()[0]['name'];
        $listName = $this->getBoardsData()[0]['lists'][0]['name'];
        $burndownGenerator->addBoard($bordName);
        $burndownGenerator->addTodoList($listName);

        $this->assertInstanceOf(Cardlist::class, $burndownGenerator->getTodoLists()[0]);
        $this->assertEquals($listName, $burndownGenerator->getTodoLists()[0]->getName());
        $this->assertCount(1, $burndownGenerator->getTodoLists());

        $listName = $this->getBoardsData()[0]['lists'][1]['name'];
        $burndownGenerator->addTodoList($listName);

        $this->assertInstanceOf(Cardlist::class, $burndownGenerator->getTodoLists()[1]);
        $this->assertCount(2, $burndownGenerator->getTodoLists());
    }

    /**
     * Test adding Todo list with board name specified.
     */
    public function testAddTodoListWithBoard()
    {
        $burndownGenerator = new BurndownGenerator($this->getTrelloClientMock());
        $bordName = $this->getBoardsData()[0]['name'];
        $listName = $this->getBoardsData()[0]['lists'][0]['name'];
        $burndownGenerator->addBoard($bordName);
        $board = $burndownGenerator->getBoards()[0];
        $burndownGenerator->addTodoList($listName, $board->getName());

        $this->assertInstanceOf(Cardlist::class, $burndownGenerator->getTodoLists()[0]);
        $this->assertEquals($listName, $burndownGenerator->getTodoLists()[0]->getName());
        $this->assertCount(1, $burndownGenerator->getTodoLists());

        $listName = $this->getBoardsData()[0]['lists'][1]['name'];
        $burndownGenerator->addTodoList($listName, $board->getName());

        $this->assertInstanceOf(Cardlist::class, $burndownGenerator->getTodoLists()[1]);
        $this->assertCount(2, $burndownGenerator->getTodoLists());
    }

    /**
     * Test adding WIP lists.
     */
    public function testAddWipListWithoutBoard()
    {
        $burndownGenerator = new BurndownGenerator($this->getTrelloClientMock());
        $bordName = $this->getBoardsData()[0]['name'];
        $listName = $this->getBoardsData()[0]['lists'][0]['name'];
        $burndownGenerator->addBoard($bordName);
        $burndownGenerator->addWipList($listName);

        $this->assertInstanceOf(Cardlist::class, $burndownGenerator->getWipLists()[0]);
        $this->assertEquals($listName, $burndownGenerator->getWipLists()[0]->getName());
        $this->assertCount(1, $burndownGenerator->getWipLists());

        $listName = $this->getBoardsData()[0]['lists'][1]['name'];
        $burndownGenerator->addWipList($listName);

        $this->assertInstanceOf(Cardlist::class, $burndownGenerator->getWipLists()[1]);
        $this->assertCount(2, $burndownGenerator->getWipLists());
    }

    /**
     * Test adding WIP lists with board Name specified.
     */
    public function testAddWipListWithBoard()
    {
        $burndownGenerator = new BurndownGenerator($this->getTrelloClientMock());
        $bordName = $this->getBoardsData()[0]['name'];
        $listName = $this->getBoardsData()[0]['lists'][0]['name'];
        $burndownGenerator->addBoard($bordName);
        $board = $burndownGenerator->getBoards()[0];
        $burndownGenerator->addWipList($listName, $board->getName());

        $this->assertInstanceOf(Cardlist::class, $burndownGenerator->getWipLists()[0]);
        $this->assertEquals($listName, $burndownGenerator->getWipLists()[0]->getName());
        $this->assertCount(1, $burndownGenerator->getWipLists());

        $listName = $this->getBoardsData()[0]['lists'][1]['name'];
        $burndownGenerator->addWipList($listName, $board->getName());

        $this->assertInstanceOf(Cardlist::class, $burndownGenerator->getWipLists()[1]);
        $this->assertCount(2, $burndownGenerator->getWipLists());
    }

    /**
     * Test adding done list.
     */
    public function testAddDoneListWithoutBoard()
    {
        $burndownGenerator = new BurndownGenerator($this->getTrelloClientMock());
        $bordName = $this->getBoardsData()[0]['name'];
        $listName = $this->getBoardsData()[0]['lists'][0]['name'];
        $burndownGenerator->addBoard($bordName);
        $burndownGenerator->addDoneList($listName);

        $this->assertInstanceOf(Cardlist::class, $burndownGenerator->getDoneLists()[0]);
        $this->assertEquals($listName, $burndownGenerator->getDoneLists()[0]->getName());
        $this->assertCount(1, $burndownGenerator->getDoneLists());

        $listName = $this->getBoardsData()[0]['lists'][1]['name'];
        $burndownGenerator->addDoneList($listName);

        $this->assertInstanceOf(Cardlist::class, $burndownGenerator->getDoneLists()[1]);
        $this->assertCount(2, $burndownGenerator->getDoneLists());
    }

    /**
     * Test adding done list with board specified.
     */
    public function testAddDoneListWithBoard()
    {
        $burndownGenerator = new BurndownGenerator($this->getTrelloClientMock());
        $bordName = $this->getBoardsData()[0]['name'];
        $listName = $this->getBoardsData()[0]['lists'][0]['name'];
        $burndownGenerator->addBoard($bordName);
        $board = $burndownGenerator->getBoards()[0];
        $burndownGenerator->addDoneList($listName, $board->getName());

        $this->assertInstanceOf(Cardlist::class, $burndownGenerator->getDoneLists()[0]);
        $this->assertEquals($listName, $burndownGenerator->getDoneLists()[0]->getName());
        $this->assertCount(1, $burndownGenerator->getDoneLists());

        $listName = $this->getBoardsData()[0]['lists'][1]['name'];
        $burndownGenerator->addDoneList($listName, $board->getName());

        $this->assertInstanceOf(Cardlist::class, $burndownGenerator->getDoneLists()[1]);
        $this->assertCount(2, $burndownGenerator->getDoneLists());
    }

    /**
     * Test get StoryPointBurndown and generate an array.
     */
    public function testGetStoryPointBurndown()
    {
        $burndownGenerator = new BurndownGenerator($this->getTrelloClientMock());
        $bordName = $this->getBoardsData()[0]['name'];
        $todoList = $this->getBoardsData()[0]['lists'][0]['name'];
        $wipList = $this->getBoardsData()[0]['lists'][1]['name'];
        $doneList = $this->getBoardsData()[0]['lists'][2]['name'];

        $burndownGenerator->addBoard($bordName);
        $board = $burndownGenerator->getBoards()[0];
        $burndownGenerator->addDoneList($doneList, $board->getName());
        $burndownGenerator->addTodoList($todoList, $board->getName());
        $burndownGenerator->addWipList($wipList, $board->getName());

        $sprint = new Sprint();
        $duration = new \DateInterval('P14D');
        $start = (new  \DateTime('2016-05-24'));
        $sprint->setStart($start);
        $sprint->setDuration($duration);

        $burndown = $burndownGenerator->getStoryPointBurndown($sprint);

        $expectedBrundown =
            [
                'real' => [
                        '2016-05-24' => 42,
                        '2016-05-25' => 42,
                        '2016-05-26' => 42,
                        '2016-05-27' => 42,
                        '2016-05-30' => 37,
                        '2016-05-31' => 28,
                        '2016-06-01' => 16,
                        '2016-06-02' => 2,
                        '2016-06-03' => -12,
                    ],
                'theorical' => [
                        '2016-05-24' => 42,
                        '2016-05-25' => 37.33,
                        '2016-05-26' => 32.66,
                        '2016-05-27' => 27.99,
                        '2016-05-30' => 23.32,
                        '2016-05-31' => 18.65,
                        '2016-06-01' => 13.98,
                        '2016-06-02' => 9.31,
                        '2016-06-03' => 4.64,
                        '2016-06-06' => -0.03,
                    ],
            ];

        $this->assertEquals($expectedBrundown, $burndown->generate());
    }
}
