<?php

namespace TrelloBurndown\Tests\Model;

use TrelloBurndown\Model\Sprint;

/**
 * Class SprintTest.
 */
class SprintTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Test Sprint.
     */
    public function testSprint()
    {
        $sprint = new Sprint();

        $this->assertInstanceOf(Sprint::class, $sprint);
        $this->assertObjectHasAttribute('start', $sprint);
        $this->assertObjectHasAttribute('duration', $sprint);
    }

    /**
     * Test Start getter and setter.
     */
    public function testStart()
    {
        $sprint = new Sprint();
        $start = new  \DateTime();
        $sprint->setStart($start);

        $this->assertEquals($sprint->getStart(), $start);
    }

    /**
     * Test Duration getter and setter.
     */
    public function testDuration()
    {
        $sprint = new Sprint();
        $duration = new \DateInterval('P14D');
        $sprint->setDuration($duration);

        $this->assertEquals($sprint->getDuration(), $duration);
    }

    /**
     * Test End calculation.
     */
    public function testEnd()
    {
        $sprint = new Sprint();
        $duration = new \DateInterval('P14D');
        $start = new  \DateTime();

        //Test without value (calculated end date)
        $sprint->setStart($start);
        $sprint->setDuration($duration);
        $end = clone $start;
        $end->add($duration);
        $this->assertEquals($sprint->getEnd(), $end);

        //Test with value (Real case)
        $sprint->setStart(new \DateTime('2016-04-12'));
        $sprint->setDuration($duration);
        $this->assertEquals($sprint->getEnd()->format('Y-m-d'), '2016-04-26');
    }

    /**
     * Test get sprint days.
     */
    public function testGetSprintDays()
    {
        $sprint = new Sprint();
        $sprint->setStart(new \DateTime('2016-04-12'));
        $sprint->setDuration($duration = new \DateInterval('P14D'));

        $days = new \DatePeriod(new \DateTime('2016-04-13'), new \DateInterval('P1D'), new \DateTime('2016-04-26'));

        $this->assertEquals($sprint->getSprintDays(), $days);
    }

    /**
     * Test get the next day in sprint.
     */
    public function testGetNextDayInSprint()
    {
        $sprint = new Sprint();
        $today = new \DateTime();
        $today->setTime(0, 0, 0);

        if ($today->format('N') == 5) {
            $this->assertEquals($sprint->getNextDayInSprint(), $today->modify('+3 days'));
        } elseif ($today->format('N') == 6) {
            $this->assertEquals($sprint->getNextDayInSprint(), $today->modify('+2 days'));
        } else {
            $this->assertEquals($sprint->getNextDayInSprint(), $today->modify('+1 days'));
        }
    }

    /**
     * Test total work days calculation.
     */
    public function testGetTotalWorkDays()
    {
        $sprint = new Sprint();
        $sprint->setStart(new \DateTime('2016-04-12'));
        $sprint->setDuration($duration = new \DateInterval('P14D'));

        $this->assertEquals($sprint->getTotalWorkDays(), 9);
    }
}
