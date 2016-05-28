<?php

namespace TrelloBurndown\Tests\Mock;

/**
 * Class CardMock.
 */
class CardMock
{
    /**
     * @var
     */
    private $name;
    /**
     * @var
     */
    private $id;
    /**
     * @var
     */
    private $actions;

    /**
     * CardMock constructor.
     *
     * @param $id
     * @param $name
     */
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

    /**
     * @param $date
     * @param string $type
     * @param null   $listBefore
     * @param null   $listAfter
     */
    public function addAction($date, $type = 'updateCard', $listBefore = null, $listAfter = null)
    {
        $this->actions[] = new ActionMock($this->id, $this->name, $date, $type, $listBefore, $listAfter);
    }

    /**
     * @return mixed
     */
    public function getActions()
    {
        return $this->actions;
    }

    /**
     * @return array
     */
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

    /**
     * @return array
     */
    public function getData()
    {
        return [
           'name' => $this->name,
           'id' => $this->id,
           'actions' => $this->getActionsData(),
       ];
    }
}
