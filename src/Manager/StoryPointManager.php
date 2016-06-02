<?php

namespace TrelloBurndown\Manager;

use Trello\Model\Cardlist;
use TrelloBurndown\Client\TrelloClient;
use TrelloBurndown\Model\Sprint;

/**
 * Class StoryPointManager.
 */
class StoryPointManager
{
    /**
     * @var ActionManager
     */
    private $actionManager;

    /**
     * @var string
     */
    private $pattern = '/\(([0-9]*.?[0-9]*)\)/';

    /**
     * @var \Trello\Client
     */
    private $client;

    /**
     * StoryPointsManager constructor.
     *
     * @param TrelloClient  $trelloClient
     * @param ActionManager $actionManager
     * @param string        $pattern
     */
    public function __construct(TrelloClient $trelloClient, ActionManager $actionManager, String $pattern = null)
    {
        $this->client = $trelloClient->getClient();
        $this->actionManager = $actionManager;

        if (!is_null($pattern)) {
            $this->pattern = $pattern;
        }
    }

    /**
     * @param string $pattern
     */
    public function setPattern(String $pattern)
    {
        $this->pattern = $pattern;
    }

    /**
     * @return string
     */
    public function getPattern()
    {
        return $this->pattern;
    }

    /**
     * @param $name
     *
     * @return int
     */
    public function parseStoryPoints($name)
    {
        preg_match($this->pattern, $name, $matches);

        return isset($matches[1]) ? floatval($matches[1]) : 0;
    }

    /**
     * @param Cardlist $list
     *
     * @return int
     */
    public function countListStoryPoints(Cardlist $list)
    {
        $cards = $this->client->api('list')->cards()->all($list->getId());
        $totalSP = 0;

        foreach ($cards as $card) {
            if (!$card['closed']) {
                $totalSP += $this->parseStoryPoints($card['name']);
            }
        }

        return $totalSP;
    }

    /**
     * Return an array of done story points per day.
     *
     * @param array  $todoLists
     * @param array  $wipLists
     * @param array  $doneList
     * @param Sprint $sprint
     *
     * @return array
     */
    public function getDoneStoryPoints(array $todoLists, array $wipLists, array $doneLists, Sprint $sprint)
    {
        $doneCards = $this->actionManager->getCardsMovedFromTodoToDone($todoLists, $wipLists, $doneLists);
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
                    $countSP += $this->parseStoryPoints($card['card']);
                }
            }

            $sp[] = ['date' => $day, 'count' => $countSP];
        }

        return $sp;
    }

    /**
     * @param array  $todoLists
     * @param array  $wipLists
     * @param array  $doneLists
     * @param Sprint $sprint
     *
     * @return int
     */
    public function getTotalSprintStoryPoints(array $todoLists, array $wipLists, array $doneLists, Sprint $sprint)
    {
        $todoSP = 0;
        $wipSP = 0;

        foreach ($todoLists as $list) {
            $todoSP += $this->countListStoryPoints($list);
        }

        foreach ($wipLists as $list) {
            $wipSP += $this->countListStoryPoints($list);
        }

        $doneSP = $this->getDoneStoryPoints($todoLists, $wipLists, $doneLists, $sprint);

        $doneSP = end($doneSP);

        return $todoSP + $wipSP + $doneSP['count'];
    }

    /**
     * @param $totalOfSprint
     * @param Sprint $sprint
     *
     * @return float
     */
    public function getAverageStoryPointsPerDay($totalOfSprint, Sprint $sprint)
    {
        return round($totalOfSprint / $sprint->getTotalWorkDays(), 2);
    }
}
