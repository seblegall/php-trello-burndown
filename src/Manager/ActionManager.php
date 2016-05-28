<?php

namespace TrelloBurndown\Manager;

use TrelloBurndown\Client\TrelloClient;

/**
 * Class ActionManager.
 */
class ActionManager
{
    /**
     * @var \Trello\Client
     */
    private $client;

    /**
     * ActionManager constructor.
     *
     * @param TrelloClient $trelloClient
     */
    public function __construct(TrelloClient $trelloClient)
    {
        $this->client = $trelloClient->getClient();
    }

    /**
     * @param array $todoLists
     * @param array $wipLists
     * @param array $doneLists
     *
     * @return array
     */
    public function getCardsMovedFromTodoToDone(array $todoLists, array $wipLists, array $doneLists)
    {
        // Get all actions on done lists.
        $actionsOnDoneLists = [];
        foreach ($doneLists as $list) {
            $actionsOnDoneLists = array_merge(
                $actionsOnDoneLists,
                $this->client
                    ->api('lists')
                    ->actions()
                    ->all($list->getId(), ['filter' => 'updateCard', 'limit' => '100'])
            );
        }

        // Get all done cards
        $doneCards = [];
        foreach ($doneLists as $list) {
            $doneCards = array_merge(
                $doneCards,
                $this->client
                    ->api('lists')
                    ->cards()
                    ->all($list->getId()));
        }

        // Get ids of done cards
        $doneCardIds = [];
        foreach ($doneCards as $card) {
            $doneCardIds[] = $card['id'];
        }

        // Get todo list Ids
        $todoListsIds = [];
        foreach ($todoLists as $list) {
            $todoListsIds[] = $list->getId();
        }

        // Get wip list Ids
        $wipListsIds = [];
        foreach ($wipLists as $list) {
            $wipListsIds[] = $list->getId();
        }

        // Get done list Ids
        $doneListsIds = [];
        foreach ($doneLists as $list) {
            $doneListsIds[] = $list->getId();
        }

        $cardFromTodoToDone = [];

        foreach ($actionsOnDoneLists as $action) {
            if ($action['type'] == 'updateCard' &&
                isset($action['data']['listBefore']) &&
                isset($action['data']['listAfter']) &&
                ((in_array($action['data']['listBefore']['id'], $todoListsIds) &&
                        in_array($action['data']['old']['idList'], $todoListsIds)) ||
                    (in_array($action['data']['listBefore']['id'], $wipListsIds) &&
                        in_array($action['data']['old']['idList'], $wipListsIds)))
                &&
                in_array($action['data']['listAfter']['id'], $doneListsIds) &&
                in_array($action['data']['card']['id'], $doneCardIds)
            ) {
                if (!isset($cardFromTodoToDone[$action['data']['card']['id']])) {
                    $cardFromTodoToDone[$action['data']['card']['id']] = [
                        'date' => $action['date'],
                        'card' => $action['data']['card']['name'],
                    ];
                } elseif ($action['date'] > $cardFromTodoToDone[$action['data']['card']['id']]['date']) {
                    $cardFromTodoToDone[$action['data']['card']['id']] = [
                        'date' => $action['date'],
                        'card' => $action['data']['card']['name'],
                    ];
                }
            }
        }

        return $cardFromTodoToDone;
    }
}
