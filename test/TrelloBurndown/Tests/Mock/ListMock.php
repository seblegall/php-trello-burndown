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
    private $actions = [];
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
        $data = [
            'name' => $this->name,
            'id' => $this->id,
        ];

        return $data;
    }
}
