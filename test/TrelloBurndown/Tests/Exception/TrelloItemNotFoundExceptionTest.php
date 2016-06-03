<?php

namespace TrelloBurndown\Tests\Exception;


use TrelloBurndown\Exception\TrelloItemNotFoundException;

class TrelloItemNotFoundExceptionTest extends \PHPUnit_Framework_TestCase
{

    public function testBoardException()
    {
        $notFoundException = new TrelloItemNotFoundException('board', 'test');

        $this->assertEquals('test', $notFoundException->getItemName());
        $this->assertEquals('board', $notFoundException->getItemType());
        $this->assertEquals(1, preg_match('/board/', $notFoundException->getMessage()));
        $this->assertEquals(1, preg_match('/test/', $notFoundException->getMessage()));
    }

    public function testCardException()
    {
        $notFoundException = new TrelloItemNotFoundException('card', 'test');

        $this->assertEquals('test', $notFoundException->getItemName());
        $this->assertEquals('card', $notFoundException->getItemType());
        $this->assertEquals(1, preg_match('/card/', $notFoundException->getMessage()));
        $this->assertEquals(1, preg_match('/test/', $notFoundException->getMessage()));
    }

    public function testListException()
    {
        $notFoundException = new TrelloItemNotFoundException('list', 'test');

        $this->assertEquals('test', $notFoundException->getItemName());
        $this->assertEquals('list', $notFoundException->getItemType());
        $this->assertEquals(1, preg_match('/list/', $notFoundException->getMessage()));
        $this->assertEquals(1, preg_match('/test/', $notFoundException->getMessage()));
    }

    public function testExceptionItemNotExist()
    {
        $notFoundException = new TrelloItemNotFoundException('test', 'test');

        $this->assertEquals('test', $notFoundException->getItemName());
        $this->assertEquals('test', $notFoundException->getItemType());
        $this->assertEquals('The declare Trello item type does not match any known type.', $notFoundException->getMessage());

    }
}