<?php

namespace TrelloBurndown\Tests\Manager;

use Trello\Model\Board;
use TrelloBurndown\Manager\BoardManager;

/**
 * Class BoardManagerTest
 */
class BoardManagerTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @return \PHPUnit_Framework_MockObject_MockObject
     */
    private function getTrelloClientMock()
    {
        $trelloClient = $this->getMockBuilder('TrelloBurnDown\Client\TrelloClient')
            ->disableOriginalConstructor()
            ->getMock();
        $trelloClient->method('getClient')
            ->willReturn($this->getClientMock());

        return $trelloClient;
    }

    /**
     * @return \PHPUnit_Framework_MockObject_MockObject
     */
    private function getClientMock()
    {
        $client = $this->getMockBuilder('Trello\Client')
            ->disableOriginalConstructor()
            ->setMethods(['api'])
            ->getMock();

        $client->expects($this->any())
            ->method('api')
            ->will($this->returnCallback(array($this, 'getApiMock')));

        return $client;
    }

    /**
     * @param $api
     * @return \PHPUnit_Framework_MockObject_MockObject
     */
    public function getApiMock($api)
    {
        if ($api == "member") {
            return $this->getApiMemberMock();
        }
        elseif ($api == "board") {
            return $this->getBoardApiMock();
        }
    }

    /**
     * @return \PHPUnit_Framework_MockObject_MockObject
     */
    function getApiMemberMock()
    {
        $memberApi = $this->getMockBuilder('Trello\Api\Member')
            ->disableOriginalConstructor()
            ->setMethods(['boards'])
            ->getMock();

        $memberApi->expects($this->any())
            ->method('boards')
            ->willReturn($this->getMemberBoardApiMock());

        return $memberApi;
    }

    /**
     * @return \PHPUnit_Framework_MockObject_MockObject
     */
    private function getBoardApiMock()
    {
        $board = $this->getMockBuilder('Trello\Api\Board')

                ->disableOriginalConstructor()
            ->setMethods(['show'])
            ->getMock();

        $board->expects($this->any())
        ->method('show')
        ->willReturn($this->getBoardsData()[0]);

        return $board;
    }

    /**
     * @return \PHPUnit_Framework_MockObject_MockObject
     */
    private function getMemberBoardApiMock()
    {
        $boardApi = $this->getMockBuilder('Trello\Api\Member\Boards')
            ->disableOriginalConstructor()
            ->setMethods(['all'])
            ->getMock();

        $boardApi->expects($this->any())
            ->method('all')
            ->with($this->equalTo('me'))
            ->willReturn($this->getBoardsData());

        return $boardApi;
    }

    /**
     * @return array
     */
    private function getBoardsData()
    {
        return [
            [
                "name" => "test 1",
                "id" => "1"
            ],
            [
                "name" => "test 2",
                "id" => "2"
            ]
        ];
    }

    /**
     * Test get board manager
     */
    public function testBoardManager()
    {
        $trelloClient = $this->getTrelloClientMock();
        $boardManager = new BoardManager($trelloClient);

        $this->assertInstanceOf(BoardManager::class, $boardManager);
    }

    /**
     * Test get board by name
     */
    public function testGetBoard()
    {
        $trelloClient = $this->getTrelloClientMock();
        $boardManager = new BoardManager($trelloClient);

        $this->assertInstanceOf(Board::class, $boardManager->getBoard("test 1"));
        $this->assertEquals("test 1", $boardManager->getBoard("test 1")->getName());
        $this->assertEquals("1", $boardManager->getBoard("test 1")->getId());
    }

    /**
     * Test exception when board cannot be find
     *
     * @expectedException \Exception
     * @expectedExceptionMessage Board test 3 not found
     */
    public function testGetBoardWithWrongName()
    {
        $trelloClient = $this->getTrelloClientMock();
        $boardManager = new BoardManager($trelloClient);
        $boardManager->getBoard("test 3");
    }

}