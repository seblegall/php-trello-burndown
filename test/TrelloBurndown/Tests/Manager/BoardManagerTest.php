<?php

namespace TrelloBurndown\Tests\Manager;

use Trello\Model\Board;
use TrelloBurndown\Manager\BoardManager;
use TrelloBurndown\Tests\AbstractTestCase;

/**
 * Class BoardManagerTest.
 */
class BoardManagerTest extends AbstractTestCase
{
    /**
     * Test get board manager.
     */
    public function testBoardManager()
    {
        $trelloClient = $this->getTrelloClientMock();
        $boardManager = new BoardManager($trelloClient);

        $this->assertInstanceOf(BoardManager::class, $boardManager);
    }

    /**
     * Test get board by name.
     */
    public function testGetBoard()
    {
        $trelloClient = $this->getTrelloClientMock();
        $boardManager = new BoardManager($trelloClient);

        $this->assertInstanceOf(Board::class, $boardManager->getBoard('test 1'));
        $this->assertEquals('test 1', $boardManager->getBoard('test 1')->getName());
        $this->assertEquals('1', $boardManager->getBoard('test 1')->getId());
    }

    /**
     * Test exception when board cannot be find.
     *
     * @expectedException \Exception
     * @expectedExceptionMessage Board test 3 not found
     */
    public function testGetBoardWithWrongName()
    {
        $trelloClient = $this->getTrelloClientMock();
        $boardManager = new BoardManager($trelloClient);
        $boardManager->getBoard('test 3');
    }
}
