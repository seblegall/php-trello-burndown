<?php

namespace TrelloBurndown\Tests\Manager;

/**
 * Class AbstractManagerTest.
 */
abstract class AbstractManagerTest extends \PHPUnit_Framework_TestCase
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
     * @return mixed
     */
    abstract protected function getApiMock($api);
}
