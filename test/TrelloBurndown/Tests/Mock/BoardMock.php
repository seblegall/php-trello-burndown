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

        for ($i = 1; $i <= 3; ++$i) {
            $listName = $this->name.' list '.$id;
            $list = new ListMock($listName, $i);

            for ($j = 1; $j <= 4; ++$j) {
                $cardName = '('.($i + $j).') Card '.(strval($i).strval($j));
                $cardId = (int) (strval($i).strval($j));
                $card = new CardMock($cardId, $cardName);

                $actionDate = (new \DateTime())->modify('-'.$j.' days')->format('Y-m-d');
                $listBefore = ($i - 1) == 0 ? $i : ($i - 1);
                $card->addAction($actionDate, 'updateCard', $listBefore, $i);
                $list->addCard($card);
            }
            $this->lists[] = $list;
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

    /**
     * @return array
     */
    public function getLists()
    {
        return $this->lists;
    }
}
