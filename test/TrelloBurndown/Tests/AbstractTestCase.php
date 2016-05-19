<?php

namespace TrelloBurndown\Tests;

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
        return [
            [
                'name' => 'test 1',
                'id' => '1',
                'lists' => [
                    [
                        'name' => 'test list 1',
                        'id' => '1',
                    ],
                    [
                        'name' => 'test list 2',
                        'id' => '2',
                    ],
                ],
            ],
            [
                'name' => 'test 2',
                'id' => '2',
                'lists' => [
                    [
                        'name' => 'test list 3',
                        'id' => '1',
                    ],
                    [
                        'name' => 'test list 4',
                        'id' => '2',
                    ],
                ],
            ],
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
            ->willReturn($this->getBoardsData()[0]['lists'][0]);

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
            return $this->getBoardsData()[0]['lists'];
        } elseif ($board == '2') {
            return $this->getBoardsData()[1]['lists'];
        }

        return;
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
}
