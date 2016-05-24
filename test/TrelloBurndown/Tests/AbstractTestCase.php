<?php

namespace TrelloBurndown\Tests;

use TrelloBurndown\Tests\Mock\BoardMock;

/**
 * Class AbstractTestCase.
 */
abstract class AbstractTestCase  extends \PHPUnit_Framework_TestCase
{
    /**
     * @return \PHPUnit_Framework_MockObject_MockObject
     */
    protected function getTrelloClientMock()
    {
        $trelloClient = $this->getMockBuilder('TrelloBurnDown\Client\TrelloClient')
            ->disableOriginalConstructor()
            ->setMethods(['getClient'])
            ->getMock();
        $trelloClient->method('getClient')
            ->willReturn($this->getClientMock());

        return $trelloClient;
    }

    /**
     * @return \PHPUnit_Framework_MockObject_MockObject
     */
    protected function getClientMock()
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
     *
     * @return \PHPUnit_Framework_MockObject_MockObject
     */
    public function getApiMock($api)
    {
        switch ($api) {
            case 'member':
                $api = $this->getApiMemberMock();
                break;
            case 'board':
                $api = $this->getBoardApiMock();
                break;
            case 'list':
            case 'lists':
                $api = $this->getListsApiMock();
                break;
            default:
                $api = null;
        }

        return $api;
    }

    /**
     * @return \PHPUnit_Framework_MockObject_MockObject
     */
    protected function getApiMemberMock()
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
    protected function getBoardApiMock()
    {
        $board = $this->getMockBuilder('Trello\Api\Board')
            ->disableOriginalConstructor()
            ->setMethods(['show', 'lists'])
            ->getMock();

        $board->expects($this->any())
            ->method('show')
            ->willReturn($this->getBoardsData()[0]);

        $board->expects($this->any())
            ->method('lists')
            ->willReturn($this->getBoardCardlistsApiMock());

        return $board;
    }

    /**
     * @return \PHPUnit_Framework_MockObject_MockObject
     */
    protected function getMemberBoardApiMock()
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
    protected function getBoardsData()
    {
        $boardOne = new BoardMock('test 1', 1);
        $boardTwo = new BoardMock('test 2', 2);

        return [$boardOne->getData(), $boardTwo->getData()];
    }

    /**
     * @return \PHPUnit_Framework_MockObject_MockObject
     */
    protected function getListsApiMock()
    {
        $list = $this->getMockBuilder('Trello\Api\Cardlist')
            ->disableOriginalConstructor()
            ->setMethods(['show', 'getFields', 'actions'])
            ->getMock();

        $list->expects($this->any())
            ->method('show')
            ->willReturn($this->getBoardsData()[0]['lists'][0]);

        $list->expects($this->any())
            ->method('getFields')
            ->willReturn(['name', 'id']);

        $list->expects($this->any())
            ->method('actions')
            ->willReturn($this->getListActionsApiMock());

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
     * @return \PHPUnit_Framework_MockObject_MockObject
     */
    protected function getListActionsApiMock()
    {
        $cardListApi = $this->getMockBuilder('Trello\Api\Cardlist\Actions')
            ->disableOriginalConstructor()
            ->setMethods(['all'])
            ->getMock();

        $cardListApi->expects($this->any())
            ->method('all')
            ->will($this->returnCallback(array($this, 'getActionsData')));

        return $cardListApi;
    }

    /**
     * @param $list
     * @param $params
     *
     * @return array|void
     */
    public function getActionsData($list, $params)
    {
        if ($list == '1') {
            return $this->getBoardsData()[0]['lists'][0]['actions'];
        } elseif ($list == '2') {
            return $this->getBoardsData()[1]['lists'][1]['actions'];
        }

        return;
    }

    /**
     * @param $board
     *
     * @return array|void
     */
    public function getListsData($board)
    {
        if ($board == '1') {
            return $this->getBoardsData()[0]['lists'];
        } elseif ($board == '2') {
            return $this->getBoardsData()[1]['lists'];
        }

        return;
    }

    /**
     * @return \PHPUnit_Framework_MockObject_MockObject
     */
    protected function getBoardMock($id)
    {
        $board = $this->getMockBuilder('Trello\Model\Board')
            ->disableOriginalConstructor()
            ->setMethods(['getId'])
            ->getMock();

        $board->expects($this->any())
            ->method('getId')
            ->willReturn($id);

        return $board;
    }

    protected function getListMock($id)
    {
        $board = $this->getMockBuilder('Trello\Model\CardList')
            ->disableOriginalConstructor()
            ->setMethods(['getId'])
            ->getMock();

        $board->expects($this->any())
            ->method('getId')
            ->willReturn($id);

        return $board;
    }
}
