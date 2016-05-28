<?php

namespace TrelloBurndown\Tests\Manager;

use TrelloBurndown\Manager\ActionManager;
use TrelloBurndown\Tests\AbstractTestCase;

/**
 * Class ActionManagerTest.
 */
class ActionManagerTest extends AbstractTestCase
{
    /**
     * @param $listId
     *
     * @return array
     */
    private function getCardsMovedFromTodoToDoneProvider($listId)
    {
        $actions = $this->getActionsData($listId);
        $returnArray = [];

        foreach ($actions as $action) {
            $returnArray[$action['data']['card']['id']] = [
                'date' => $action['date'],
                'card' => $action['data']['card']['name'],
            ];
        }

        return $returnArray;
    }

    /**
     * Test ActionManager.
     */
    public function testActionManager()
    {
        $trelloClient = $this->getTrelloClientMock();
        $actionManager = new ActionManager($trelloClient);

        $this->assertInstanceOf(ActionManager::class, $actionManager);
    }

    /**
     * Test simple case of getCardsMovedFromTodoToDone Method.
     */
    public function testGetCardsMoved()
    {
        $trelloClient = $this->getTrelloClientMock();

        $todoLists = [$this->getListMock('1')];
        $wipLists = [$this->getListMock('2')];
        $doneLists = [$this->getListMock('3')];
        $actionManager = new ActionManager($trelloClient);
        $cards = $actionManager->getCardsMovedFromTodoToDone($todoLists, $wipLists, $doneLists);

        $this->assertEquals($this->getCardsMovedFromTodoToDoneProvider('3'), $cards);
    }
}
