<?php

namespace TrelloBurndown\Model;

/**
 * Class StoryPointBurndown.
 */
class StoryPointBurndown
{
    /**
     * @var float
     */
    private $averageSP;
    /**
     * @var array
     */
    private $doneSP;
    /**
     * @var float
     */
    private $totalSP;
    /**
     * @var Sprint
     */
    private $sprint;

    /**
     * @var string
     */
    private static $dateFormat = 'Y-m-d';

    /**
     * StoryPointBurndown constructor.
     *
     * @param Sprint $sprint
     * @param float  $totalSP
     * @param array  $doneSP
     * @param float  $averageSP
     */
    public function __construct(Sprint $sprint, float $totalSP, array $doneSP, float $averageSP)
    {
        $this->sprint = $sprint;
        $this->totalSP = $totalSP;
        $this->doneSP = $doneSP;
        $this->averageSP = $averageSP;
    }

    /**
     * @return float
     */
    public function getAverageSP()
    {
        return $this->averageSP;
    }

    /**
     * @param float $averageSP
     */
    public function setAverageSP($averageSP)
    {
        $this->averageSP = $averageSP;
    }

    /**
     * @return array
     */
    public function getDoneSP()
    {
        return $this->doneSP;
    }

    /**
     * @param array $doneSP
     */
    public function setDoneSP($doneSP)
    {
        $this->doneSP = $doneSP;
    }

    /**
     * @return float
     */
    public function getTotalSP()
    {
        return $this->totalSP;
    }

    /**
     * @param float $totalSP
     */
    public function setTotalSP($totalSP)
    {
        $this->totalSP = $totalSP;
    }

    /**
     * @return Sprint
     */
    public function getSprint()
    {
        return $this->sprint;
    }

    /**
     * @param Sprint $sprint
     */
    public function setSprint($sprint)
    {
        $this->sprint = $sprint;
    }

    public function formatDate(\DateTime $date)
    {
        return $date->format(self::$dateFormat);
    }

    /**
     * @return array
     */
    public function getRealBurndown()
    {
        $realBurndown = [];
        $total = $this->totalSP;
        $realBurndown[$this->formatDate($this->sprint->getStart())] = $total;
        foreach ($this->doneSP as $sp) {
            $total = $total - $sp['count'];
            $realBurndown[$this->formatDate($sp['date'])] = $total;
        }

        return $realBurndown;
    }

    /**
     * @return array|null
     */
    public function getTheoreticalBurndown()
    {
        $theoreticalBurndown = [];
        $theoreticalBurndown[$this->sprint->getStart()->format('Y-m-d')] = $this->totalSP;

        $sprintDays = $this->sprint->getSprintDays();
        if (!$sprintDays instanceof \DatePeriod) {
            return;
        }

        foreach ($sprintDays as $day) {
            if ($day instanceof \DateTime && ($day->format('N') == 6 || $day->format('N') == 7)) {
                continue;
            }

            $rest = end($theoreticalBurndown) != false ? end($theoreticalBurndown) : $this->totalSP;
            $formatedDate = $this->formatDate($day);
            $doneSP = $rest - $this->averageSP;
            $theoreticalBurndown[$formatedDate] = round($doneSP, 2);
        }

        return $theoreticalBurndown;
    }

    /**
     * @return array
     */
    public function generate()
    {
        $real = $this->getRealBurndown();
        $ideal = $this->getTheoreticalBurndown();

        return ['real' => $real, 'theorical' => $ideal];
    }
}
