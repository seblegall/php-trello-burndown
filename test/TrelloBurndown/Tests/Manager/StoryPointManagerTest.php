<?php

namespace TrelloBurndown\Tests\Manager;

use TrelloBurndown\Manager\ActionManager;
use TrelloBurndown\Manager\StoryPointManager;
use TrelloBurndown\Model\Sprint;
use TrelloBurndown\Tests\AbstractTestCase;

/**
 * Class StoryPointManagerTest.
 */
class StoryPointManagerTest extends AbstractTestCase
{
    /**
     * Test StoryPointManager class.
     */
    public function testStoryPointManager()
    {
        $trelloClient = $this->getTrelloClientMock();
        $actionManager = new ActionManager($trelloClient);
        $storyPointManager = new StoryPointManager($trelloClient, $actionManager);

        $this->assertInstanceOf(StoryPointManager::class, $storyPointManager);
    }

    /**
     * Test setting a parttern.
     */
    public function testStoryPointManagerWithPattern()
    {
        $pattern = '/\[([0-9]*.?[0-9]*)\]/';

        $trelloClient = $this->getTrelloClientMock();
        $actionManager = new ActionManager($trelloClient);
        $storyPointManager = new StoryPointManager($trelloClient, $actionManager, $pattern);

        $this->assertInstanceOf(StoryPointManager::class, $storyPointManager);
    }

    /**
     * Test setting and getting a pettern.
     */
    public function testSetPattern()
    {
        $pattern = '/\[([0-9]*.?[0-9]*)\]/';

        $trelloClient = $this->getTrelloClientMock();
        $actionManager = new ActionManager($trelloClient);
        $storyPointManager = new StoryPointManager($trelloClient, $actionManager);

        $storyPointManager->setPattern($pattern);

        $this->assertEquals($pattern, $storyPointManager->getPattern());
    }

    /**
     * Test parse story point in the card name.
     *
     * @dataProvider patternProvider
     */
    public function testParseStoryPoints($string, $sp)
    {
        $trelloClient = $this->getTrelloClientMock();
        $actionManager = new ActionManager($trelloClient);
        $storyPointManager = new StoryPointManager($trelloClient, $actionManager);
        $this->assertEquals($sp, $storyPointManager->parseStoryPoints($string));
    }

    /**
     * @return array
     */
    public function patternProvider()
    {
        return [
            ['(0.5) Test', 0.5],
            ['(1) Test', 1.0],
            ['Test', 0],
            ['(15) Test', 15.0],
            ['4', 0],
            ['Test (7)', 7.0],
            ['(17)', 17.0],
            ['(0....5) Test', 0],
        ];
    }

    /**
     * Test count story point in a list.
     */
    public function testCountListStoryPoints()
    {
        $trelloClient = $this->getTrelloClientMock();
        $actionManager = new ActionManager($trelloClient);
        $storyPointManager = new StoryPointManager($trelloClient, $actionManager);
        $list = $this->getListMock('1');
        $total = $storyPointManager->countListStoryPoints($list);

        $this->assertEquals(14, $total);
    }

    /**
     * Test done story points.
     */
    public function testGetDoneStoryPoints()
    {
        $trelloClient = $this->getTrelloClientMock();
        $actionManager = new ActionManager($trelloClient);
        $storyPointManager = new StoryPointManager($trelloClient, $actionManager);

        $todoLists = [$this->getListMock('1')];
        $wipLists = [$this->getListMock('2')];
        $doneLists = [$this->getListMock('3')];

        $sprint = $this->getSprintMock();
        $doneSP = $storyPointManager->getDoneStoryPoints($todoLists, $wipLists, $doneLists, $sprint);

        $doneCards = $actionManager->getCardsMovedFromTodoToDone($todoLists, $wipLists, $doneLists);
        $sprintDays = $sprint->getSprintDays();
        $sp = [];

        foreach ($sprintDays as $day) {
            $countSP = 0;
            if ($day instanceof \DateTime &&
                ($day->getTimestamp() > $sprint->getNextDayInSprint()->getTimestamp())) {
                break;
            }

            if ($day instanceof \DateTime && ($day->format('N') == 6 || $day->format('N') == 7)) {
                continue;
            }

            foreach ($doneCards as $card) {
                $actionDate = new \DateTime($card['date']);
                if (
                    $actionDate->getTimestamp() > $sprint->getStart()->getTimestamp() &&
                    $actionDate->getTimestamp() < $day->getTimestamp()
                ) {
                    $countSP += $storyPointManager->parseStoryPoints($card['card']);
                }
            }

            $sp[] = ['date' => $day, 'count' => $countSP];
        }


        $this->assertEquals($sp, $doneSP);
    }

    /**
     * test total of sprint story points.
     */
    public function testGetTotalSprintStoryPoints()
    {
        $trelloClient = $this->getTrelloClientMock();
        $actionManager = new ActionManager($trelloClient);
        $storyPointManager = new StoryPointManager($trelloClient, $actionManager);

        $todoLists = [$this->getListMock('1')];
        $wipLists = [$this->getListMock('2')];
        $doneLists = [$this->getListMock('3')];

        $sprint = $this->getSprintMock();
        $total = $storyPointManager->getTotalSprintStoryPoints($todoLists, $wipLists, $doneLists, $sprint);

        $this->assertEquals(54.0, $total);
    }

    /**
     * test average story point per worked days.
     */
    public function testGetAverageStoryPointsPerDay()
    {
        $trelloClient = $this->getTrelloClientMock();
        $actionManager = new ActionManager($trelloClient);
        $storyPointManager = new StoryPointManager($trelloClient, $actionManager);

        $todoLists = [$this->getListMock('1')];
        $wipLists = [$this->getListMock('2')];
        $doneLists = [$this->getListMock('3')];

        $sprint = $this->getSprintMock();
        $total = $storyPointManager->getTotalSprintStoryPoints($todoLists, $wipLists, $doneLists, $sprint);

        $this->assertEquals(5.4000000000000004, $storyPointManager->getAverageStoryPointsPerDay($total, $sprint));
    }
}
