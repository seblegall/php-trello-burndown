<?php

namespace TrelloBurndown\Tests\Mock;

/**
 * Class BoardMock.
 */
class BoardMock implements TrelloMockInterface
{
    /**
     * @var array
     */
    private $lists = [];

    /**
     * @var
     */
    private $name;

    /**
     * @var
     */
    private $id;

    /**
     * BoardMock constructor.
     *
     * @param $name
     * @param $id
     */
    public function __construct($name, $id)
    {
        $this->name = $name;
        $this->id = $id;

        for ($i = 1; $i < 3; ++$i) {
            $listName = $this->name.' list '.$id;
            $this->lists[] = new ListMock($listName, $i);
        }
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
     * @return array
     */
    public function getData()
    {
        $data = [
            'name' => $this->name,
            'id' => $this->id,
        ];

        foreach ($this->lists as $list) {
            $data['lists'][] = $list->getData();
        }

        return $data;
    }
}
