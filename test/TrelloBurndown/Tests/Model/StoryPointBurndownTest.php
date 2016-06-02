<?php

namespace TrelloBurndown\Tests\Model;

use TrelloBurndown\Model\Sprint;
use TrelloBurndown\Model\StoryPointBurndown;

/**
 * Class StoryPointBurndownTest.
 */
class StoryPointBurndownTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @return array
     */
    private function doneSPProvider()
    {
        return [
            [
                'date' => (new  \DateTime('2016-05-25')),
                'count' => 20.0,
            ],
            [
                'date' => (new  \DateTime('2016-05-26')),
                'count' => 29.0,

            ],
            [
                'date' => (new  \DateTime('2016-05-27')),
                'count' => 24.0,

            ],
            [
                'date' => (new  \DateTime('2016-05-30')),
                'count' => 23.5,

            ],
            [
                'date' => (new  \DateTime('2016-05-31')),
                'count' => 19.0,

            ],
            [
                'date' => (new  \DateTime('2016-06-01')),
                'count' => 5.0,
            ],
        ];
    }

    /**
     * test StoryPointBurndown class.
     */
    public function testSPBurndown()
    {
        $sprint = new Sprint();
        $duration = new \DateInterval('P14D');
        $start = (new  \DateTime())->modify('-6 days');
        $sprint->setStart($start);
        $sprint->setDuration($duration);
        $doneSP = $this->doneSPProvider();

        $burndown = new StoryPointBurndown($sprint, 250.0, $doneSP, 25);

        $this->assertInstanceOf(StoryPointBurndown::class, $burndown);
    }

    /**
     * test getter and setters.
     */
    public function testGetterAndSetterSPBurndown()
    {
        $sprint = new Sprint();
        $duration = new \DateInterval('P14D');
        $start = (new  \DateTime())->modify('-6 days');
        $sprint->setStart($start);
        $sprint->setDuration($duration);
        $doneSP = $this->doneSPProvider();

        $burndown = new StoryPointBurndown($sprint, 250.0, $doneSP, 25);
        $this->assertInstanceOf(StoryPointBurndown::class, $burndown);

        $burndown->setAverageSP(23);
        $this->assertEquals(23, $burndown->getAverageSP());

        $burndown->setDoneSP($doneSP);
        $this->assertEquals($doneSP, $burndown->getDoneSP());

        $burndown->setTotalSP(260);
        $this->assertEquals(260, $burndown->getTotalSP());
    }

    /**
     * Test real burndown generation.
     */
    public function testGetRealBurndown()
    {
        $sprint = new Sprint();
        $duration = new \DateInterval('P14D');
        $start = (new  \DateTime('2016-05-24'));
        $sprint->setStart($start);
        $sprint->setDuration($duration);
        $doneSP = $this->doneSPProvider();

        $burndown = new StoryPointBurndown($sprint, 250.0, $doneSP, 25);

        $realBurndown = [
            '2016-05-24' => 250.0,
            '2016-05-25' => 230.0,
            '2016-05-26' => 201.0,
            '2016-05-27' => 177.0,
            '2016-05-30' => 153.5,
            '2016-05-31' => 134.5,
            '2016-06-01' => 129.5,
        ];

        $this->assertEquals($realBurndown, $burndown->getRealBurndown());
    }

    /**
     * test theorical burndown generation.
     */
    public function testGetTheoricalBurndown()
    {
        $sprint = new Sprint();
        $duration = new \DateInterval('P14D');
        $start = (new  \DateTime('2016-05-24'));
        $sprint->setStart($start);
        $sprint->setDuration($duration);
        $doneSP = $this->doneSPProvider();

        $burndown = new StoryPointBurndown($sprint, 250.0, $doneSP, 25);

        $theoricalBurndown = [
            '2016-05-24' => 250.0,
            '2016-05-25' => 225.0,
            '2016-05-26' => 200.0,
            '2016-05-27' => 175.0,
            '2016-05-30' => 150.0,
            '2016-05-31' => 125.0,
            '2016-06-01' => 100.0,
            '2016-06-02' => 75.0,
            '2016-06-03' => 50.0,
            '2016-06-06' => 25.0,
        ];

        $this->assertEquals($theoricalBurndown, $burndown->getTheoreticalBurndown());
    }
}
