<?php

namespace TrelloBurndown\Tests\Generator;

use Trello\Model\Board;
use Trello\Model\Cardlist;
use TrelloBurndown\BurndownGenerator;
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
     * Test exception when board cannot be found.
     *
     * @expectedException TrelloBurndown\Exception\TrelloItemNotFoundException
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
     * Test Adding list when list is not found
     *
     * @expectedException TrelloBurndown\Exception\TrelloItemNotFoundException
     * @expectedExceptionMessageRegExp /abc/
     * * @expectedExceptionMessageRegExp /list/
     */
    public function testAddListWithWrongName()
    {
        $burndownGenerator = new BurndownGenerator($this->getTrelloClientMock());
        $bordName = $this->getBoardsData()[0]['name'];
        $listName = 'abc';
        $burndownGenerator->addBoard($bordName);
        $burndownGenerator->addWipList($listName);
    }

    /**
     * Test Adding list when board is not found
     *
     * @expectedException TrelloBurndown\Exception\TrelloItemNotFoundException
     * @expectedExceptionMessageRegExp /test/
     * * @expectedExceptionMessageRegExp /board/
     */
    public function testAddListWithWrongBoardName()
    {
        $burndownGenerator = new BurndownGenerator($this->getTrelloClientMock());
        $bordName = 'test';
        $listName = 'abc';
        $burndownGenerator->addWipList($listName, $bordName);
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

        $sprint = $this->getSprintMock();

        $burndown = $burndownGenerator->getStoryPointBurndown($sprint);

        $expectedBrundown =
            [
                'real' => [
                        '2016-05-24' => 42.0,
                        '2016-05-25' => 42.0,
                        '2016-05-26' => 42.0,
                        '2016-05-27' => 42.0,
                        '2016-05-30' => 33.0,
                        '2016-05-31' => 21.0,
                        '2016-06-01' => 7.0,
                        '2016-06-02' => -7.0,
                        '2016-06-03' => -21.0,
                    ],
                'theorical' => [
                    '2016-05-24' => 42.0,
                    '2016-05-25' => 37.799999999999997,
                    '2016-05-26' => 33.600000000000001,
                    '2016-05-27' => 29.399999999999999,
                    '2016-05-30' => 25.199999999999999,
                    '2016-05-31' => 21.0,
                    '2016-06-01' => 16.800000000000001,
                    '2016-06-02' => 12.6,
                    '2016-06-03' => 8.4000000000000004,
                    '2016-06-06' => 4.2000000000000002,
                    ],
            ];

        $this->assertEquals($expectedBrundown, $burndown->generate());
    }
}
