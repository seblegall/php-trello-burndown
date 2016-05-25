<?php

namespace TrelloBurndown\Tests\Mock;

/**
 * Class ListMock.
 */
class ListMock implements TrelloMockInterface
{
    /**
     * @var array
     */
    private $cards = [];
    /**
     * @var
     */
    private $name;
    /**
     * @var
     */
    private $id;

    /**
     * ListMock constructor.
     *
     * @param $name
     * @param $id
     */
    public function __construct($name, $id)
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
     * @return array
     */
    public function getData()
    {
        $actions = [];
        foreach ($this->cards as $card){
            $actions = array_merge($actions, $card->getActionsData());
        }

        $cards = [];
        foreach ($this->cards as $card) {
            $cards[] = $card->getData();
        }

        $data = [
            'name' => $this->name,
            'id' => $this->id,
            'actions' => $actions,
            'cards' =>$cards,
        ];

        return $data;
    }

    public function addCard(CardMock $card)
    {
        $this->cards[] = $card;
    }
}
