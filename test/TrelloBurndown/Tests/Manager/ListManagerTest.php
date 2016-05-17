<?php

namespace TrelloBurndown\Tests\Manager;

use Trello\Model\Cardlist;
use TrelloBurndown\Manager\ListManager;

/**
 * Class ListManagerTest.
 */
class ListManagerTest extends AbstractManagerTest
{
    /**
     * @param $api
     *
     * @return \PHPUnit_Framework_MockObject_MockObject
     */
    public function getApiMock($api)
    {
        if ($api == 'board') {
            return $this->getBoardApiMock();
        }
        if ($api == 'lists') {
            return $this->getListsApiMock();
        }
        if ($api == 'list') {
            return $this->getListsApiMock();
        }

        return;
    }

    /**
     * @return \PHPUnit_Framework_MockObject_MockObject
     */
    protected function getBoardApiMock()
    {
        $board = $this->getMockBuilder('Trello\Api\Board')
            ->disableOriginalConstructor()
            ->setMethods(['lists'])
            ->getMock();

        $board->expects($this->any())
            ->method('lists')
            ->willReturn($this->getBoardCardlistsApiMock());

        return $board;
    }

    /**
     * @return array
     */
    protected function getBoardData()
    {
        return [
            'name' => 'test 1',
            'id' => '1',
        ];
    }

    /**
     * @return \PHPUnit_Framework_MockObject_MockObject
     */
    protected function getListsApiMock()
    {
        $list = $this->getMockBuilder('Trello\Api\Cardlist')

            ->disableOriginalConstructor()
            ->setMethods(['show', 'getFields'])
            ->getMock();

        $list->expects($this->any())
            ->method('show')
            ->willReturn($this->getListsBoardOneData()[0]);

        $list->expects($this->any())
            ->method('getFields')
            ->willReturn(['name', 'id']);

        return $list;
    }

    /**
     * @return \PHPUnit_Framework_MockObject_MockObject
     */
    protected function getBoardCardlistsApiMock()
    {
        $cardListApi = $this->getMockBuilder('Trello\Api\Board\Cardlists')
            ->disableOriginalConstructor()
            ->setMethods(['all'])
            ->getMock();

        $cardListApi->expects($this->any())
            ->method('all')
            ->will($this->returnCallback(array($this, 'getListsData')));

        return $cardListApi;
    }

    /**
     * @param $board
     *
     * @return array|void
     */
    public function getListsData($board)
    {
        if ($board == '1') {
            return $this->getListsBoardOneData();
        } elseif ($board == '2') {
            return $this->getListsBoardTwoData();
        }

        return;
    }

    /**
     * @return array
     */
    protected function getListsBoardOneData()
    {
        return [
            [
                'name' => 'test list 1',
                'id' => '1',
            ],
            [
                'name' => 'test list 2',
                'id' => '2',
            ],
        ];
    }

    /**
     * @return array
     */
    protected function getListsBoardTwoData()
    {
        return [
            [
                'name' => 'test list 3',
                'id' => '1',
            ],
            [
                'name' => 'test list 4',
                'id' => '2',
            ],
        ];
    }

    /**
     * @return \PHPUnit_Framework_MockObject_MockObject
     */
    protected function getBoardOneMock()
    {
        $board = $this->getMockBuilder('Trello\Model\Board')
            ->disableOriginalConstructor()
            ->setMethods(['getId'])
            ->getMock();

        $board->expects($this->any())
            ->method('getId')
            ->willReturn('1');

        return $board;
    }

    /**
     * @return \PHPUnit_Framework_MockObject_MockObject
     */
    protected function getBoardTwoMock()
    {
        $board = $this->getMockBuilder('Trello\Model\Board')
            ->disableOriginalConstructor()
            ->setMethods(['getId'])
            ->getMock();

        $board->expects($this->any())
            ->method('getId')
            ->willReturn('2');

        return $board;
    }

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

        $board = $this->getBoardOneMock();

        $this->assertInstanceOf(Cardlist::class, $listManager->getListFromBoard('test list 1', $board));
        $this->assertEquals('test list 1', $listManager->getListFromBoard('test list 1', $board)->getName());
        $this->assertEquals('1', $listManager->getListFromBoard('test list 1', $board)->getId());
    }

    /**
     * Test get list by name with wrong name.
     */
    public function testGetListFromBoardWithName()
    {
        $trelloClient = $this->getTrelloClientMock();
        $listManager = new ListManager($trelloClient);
        $board = $this->getBoardOneMock();

        $this->assertEmpty($listManager->getListFromBoard('wrong name', $board));
    }

    /**
     * @throws \Exception
     */
    public function testGetList()
    {
        $trelloClient = $this->getTrelloClientMock();
        $listManager = new ListManager($trelloClient);
        $board1 = $this->getBoardOneMock();
        $board2 = $this->getBoardTwoMock();

        $this->assertInstanceOf(Cardlist::class, $listManager->getList('test list 1', [$board1, $board2]));
        $this->assertEquals('test list 1', $listManager->getList('test list 1', [$board1, $board2])->getName());
        $this->assertEquals('1', $listManager->getList('test list 1', [$board1, $board2])->getId());
    }

    /**
     * @throws \Exception
     */
    public function testGetListWrongName()
    {
        $trelloClient = $this->getTrelloClientMock();
        $listManager = new ListManager($trelloClient);
        $board1 = $this->getBoardOneMock();
        $board2 = $this->getBoardTwoMock();

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
        $board1 = $this->getBoardOneMock();
        $board2 = 'test';

        $this->assertEmpty($listManager->getList('test list 1', [$board1, $board2]));
    }
}
