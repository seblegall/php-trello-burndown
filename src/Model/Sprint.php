<?php

namespace TrelloBurndown\Model;

use TrelloBurndown\Helper\DateHelper;

/**
 * Class Sprint.
 */
class Sprint
{
    use DateHelper;
    /**
     * @var \DateTime
     */
    private $start;

    /**
     * @var \DateInterval
     */
    private $duration;

    /**
     * @const String
     */
    const INTERVAL = 'P1D';

    /**
     * @return \DateTime
     */
    public function getStart()
    {
        return $this->start;
    }

    /**
     * @return \DateInterval
     */
    public function getDuration()
    {
        return $this->duration;
    }

    /**
     * @param \DateTime $start
     */
    public function setStart(\DateTime $start)
    {
        $this->start = $start;
    }

    /**
     * @param \DateInterval $duration
     */
    public function setDuration(\DateInterval $duration)
    {
        $this->duration = $duration;
    }

    /**
     * Calculate the end of sprint from start and duration.
     *
     * @return \DateTime|null
     */
    public function getEnd()
    {
        if ($this->start instanceof \DateTime && $this->duration instanceof \DateInterval) {
            $end = clone $this->start;

            return $end->add($this->duration);
        }

        return;
    }

    /**
     * Get all days in the sprint from start date
     * and during duration.
     *
     * @return \DatePeriod|null
     */
    public function getSprintDays()
    {
        if ($this->start instanceof \DateTime && $this->duration instanceof \DateInterval) {
            $interval = new \DateInterval(self::INTERVAL);

            $end = $this->getEnd();

            $firstDay = clone $this->start;

            return new \DatePeriod(
                $firstDay->add($interval),
                $interval,
                $end
            );
        }

        return;
    }

    /**
     * Calculate the next day in the sprint.
     *
     * @return \DateTime
     */
    public function getNextDayInSprint()
    {
        $today = new \DateTime();
        $today->setTime(0, 0, 0);

        if ($today->format('N') == 5) {
            return $today->modify('+3 days');
        }

        if ($today->format('N') == 6) {
            return $today->modify('+2 days');
        }

        return $today->modify('+1 days');
    }

    /**
     * Calculate the total work days in the sprint.
     * This function does not return week-end days but
     * return non-work-days such as christmas.
     *
     * @return null|int
     */
    public function getTotalWorkDays()
    {
        $days = $this->getSprintDays();
        $total = 0;

        if (!$days instanceof \DatePeriod) {
            return;
        }

        foreach ($days as $day) {
            if ($this->isWeekend($day)) {
                continue;
            }
            ++$total;
        }

        return $total;
    }
}
