<?php

namespace TrelloBurndown\Tests\Manager;

use Trello\Model\Cardlist;
use TrelloBurndown\Manager\ListManager;
use TrelloBurndown\Tests\AbstractTestCase;

/**
 * Class ListManagerTest.
 */
class ListManagerTest extends AbstractTestCase
{
    /**
     * Test get list manager.
     */
    public function testListManager()
    {
        $trelloClient = $this->getTrelloClientMock();
        $listManager = new ListManager($trelloClient);

        $this->assertInstanceOf(ListManager::class, $listManager);
    }

    /**
     * Test get list by name.
     */
    public function testGetListFromBoard()
    {
        $trelloClient = $this->getTrelloClientMock();
        $listManager = new ListManager($trelloClient);

        $board = $this->getBoardMock('1');
        $listName = $this->getBoardsData()[0]['lists'][0]['name'];

        $this->assertInstanceOf(Cardlist::class, $listManager->getListFromBoard($listName, $board));
        $this->assertEquals($listName, $listManager->getListFromBoard($listName, $board)->getName());
        $this->assertEquals('1', $listManager->getListFromBoard($listName, $board)->getId());
    }

    /**
     * Test get list by name with wrong name.
     */
    public function testGetListFromBoardWithName()
    {
        $trelloClient = $this->getTrelloClientMock();
        $listManager = new ListManager($trelloClient);
        $board = $this->getBoardMock('1');

        $this->assertEmpty($listManager->getListFromBoard('wrong name', $board));
    }

    /**
     * @throws \Exception
     */
    public function testGetList()
    {
        $trelloClient = $this->getTrelloClientMock();
        $listManager = new ListManager($trelloClient);
        $board1 = $this->getBoardMock('1');
        $board2 = $this->getBoardMock('2');
        $listName = $this->getBoardsData()[0]['lists'][0]['name'];

        $this->assertInstanceOf(Cardlist::class, $listManager->getList($listName, [$board1, $board2]));
        $this->assertEquals($listName, $listManager->getList($listName, [$board1, $board2])->getName());
        $this->assertEquals('1', $listManager->getList($listName, [$board1, $board2])->getId());
    }

    /**
     * @throws \Exception
     */
    public function testGetListWrongName()
    {
        $trelloClient = $this->getTrelloClientMock();
        $listManager = new ListManager($trelloClient);
        $board1 = $this->getBoardMock('1');
        $board2 = $this->getBoardMock('2');

        $this->assertEmpty($listManager->getList('wrong name', [$board1, $board2]));
    }

    /**
     * Test exception when array does not contain Boards.
     *
     * @expectedException \Exception
     * @expectedExceptionMessage Function ListManager::getList expect an array of Board as second argument
     */
    public function testGetBoardWithWrongName()
    {
        $trelloClient = $this->getTrelloClientMock();
        $listManager = new ListManager($trelloClient);
        $board1 = $this->getBoardMock('1');
        $board2 = 'test';

        $this->assertEmpty($listManager->getList('test list 1', [$board1, $board2]));
    }
}
