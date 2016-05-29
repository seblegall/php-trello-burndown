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

        $sprint = new Sprint();
        $duration = new \DateInterval('P14D');
        $start = (new  \DateTime())->modify('-6 days');
        $sprint->setStart($start);
        $sprint->setDuration($duration);
        $doneSP = $storyPointManager->getDoneStoryPoints($todoLists, $wipLists, $doneLists, $sprint);

        $totalSP = 0;

        foreach ($doneSP as $card) {
            $totalSP += $card['count'];
        }

        $this->assertEquals(38, $totalSP);
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

        $sprint = new Sprint();
        $duration = new \DateInterval('P14D');
        $start = (new  \DateTime())->modify('-6 days');
        $sprint->setStart($start);
        $sprint->setDuration($duration);
        $total = $storyPointManager->getTotalSprintStoryPoints($todoLists, $wipLists, $doneLists, $sprint);

        $this->assertEquals(50, $total);
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

        $sprint = new Sprint();
        $duration = new \DateInterval('P14D');
        $start = (new  \DateTime())->modify('-6 days');
        $sprint->setStart($start);
        $sprint->setDuration($duration);
        $total = $storyPointManager->getTotalSprintStoryPoints($todoLists, $wipLists, $doneLists, $sprint);

        $this->assertEquals(5.56, $storyPointManager->getAverageStoryPointsPerDay($total, $sprint));
    }
}
