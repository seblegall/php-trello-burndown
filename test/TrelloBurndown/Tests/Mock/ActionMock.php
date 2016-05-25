<?php

namespace TrelloBurndown\Tests\Mock;


class ActionMock
{

    private $type;
    private $data;
    private $date;

    public function __construct($cardId, $cardName, $date, $type = 'updateCard', $listBefore = null, $listAfter = null)
    {
        $this->type = $type;
        if($this->type == 'updateCard') {
            $this->data['listBefore'] = $listBefore;
            $this->data['listAfter'] = $listAfter;
            $this->data['old']['idList'] = $listBefore;
        }
        $this->date = $date;
        $this->data['date'] = $this->date;
        $this->data['card']['id'] = $cardId;
        $this->data['card']['name'] = $cardName;

    }

    /**
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @param string $type
     */
    public function setType($type)
    {
        $this->type = $type;
    }

    /**
     * @return mixed
     */
    public function getData()
    {
        return $this->data;
    }
    
    /**
     * @return mixed
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * @param mixed $date
     */
    public function setDate($date)
    {
        $this->date = $date;
    }
    
    
}