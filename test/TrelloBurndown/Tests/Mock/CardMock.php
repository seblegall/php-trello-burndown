<?php

namespace TrelloBurndown\Tests\Mock;


class CardMock
{
    private $name;
    private $id;
    private $actions;

    public function __construct($id, $name)
    {
        $this->name = $name;
        $this->id = $id;
    }

    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param mixed $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param mixed $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    public function addAction($date, $type = 'updateCard', $listBefore = null, $listAfter = null)
    {
        $this->actions[] = new ActionMock($this->id, $this->name, $date, $type, $listBefore, $listAfter);
    }

    public function getActions()
    {
        return $this->actions;
    }

    public function getActionsData()
    {
        $actions = [];
        foreach ($this->actions as $action) {
            $actions[] = [
                'type' => $action->getType(),
                'date' => $action->getDate(),
                'data' => $action->getData(),
        ];
        }

        return $actions;
    }

    public function getData()
    {
       return [
           'name' => $this->name,
           'id' => $this->id,
           'actions' => $this->getActionsData(),
       ];
    }

}