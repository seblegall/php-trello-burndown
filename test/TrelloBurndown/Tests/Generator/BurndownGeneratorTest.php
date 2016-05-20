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
}
