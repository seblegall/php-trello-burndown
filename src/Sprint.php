<?php

namespace PhpTrelloBurndown;

/**
 * Class Sprint.
 */
class Sprint
{
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
     * @return \DateTime|null
     */
    public function getEnd()
    {
        if ($this->start instanceof \DateTime && $this->duration instanceof \DateInterval) {
            $end = clone $this->start;

            return $end->add($this->duration);
        }

        return null;
    }

    /**
     * @return \DatePeriod|null
     */
    public function getSprintDays()
    {
        if ($this->start instanceof \DateTime && $this->duration instanceof \DateInterval) {
            $interval = new \DateInterval(self::INTERVAL);

            $end = $this->getEnd();

            $firstDay = clone $this->start;

            return new \DatePeriod(
                $firstDay->add(new \DateInterval('P1D')),
                $interval,
                $end
            );
        }

        return null;
    }

    /**
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
     * @return int
     */
    public function getTotalWorkDays()
    {
        $days = $this->getSprintDays();

        $total = 0;

        foreach ($days as $day) {
            if ($day instanceof \DateTime && ($day->format('N') == 6 || $day->format('N') == 7)) {
                continue;
            }

            ++$total;
        }

        return $total;
    }
}
