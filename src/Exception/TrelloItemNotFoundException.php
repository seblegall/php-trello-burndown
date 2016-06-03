<?php

namespace TrelloBurndown\Exception;

/**
 * Class TrelloItemNotFoundException.
 */
class TrelloItemNotFoundException extends \Exception
{
    /**
     * @var array
     */
    private static $itemTypes = [
        'board',
        'list',
        'card',
    ];

    /**
     * @var string
     */
    private $itemType;

    /**
     * @var string
     */
    private $itemName;

    /**
     * TrelloItemNotFoundException constructor.
     *
     * @param string          $itemType
     * @param string          $itemName
     * @param int             $code
     * @param \Exception|null $previous
     */
    public function __construct(String $itemType, String $itemName, $code = 0, \Exception $previous = null)
    {
        $this->itemName = $itemName;
        $this->itemType = $itemType;

        try {
            $message = self::getNotFoundMessage($itemType, $itemName);
            parent::__construct($message, 404, $previous);
        } catch (\Exception $e) {
            parent::__construct($e->getMessage());
        }
    }

    /**
     * @param $itemType
     * @param $itemName
     *
     * @return string
     *
     * @throws \Exception
     */
    private static function getNotFoundMessage($itemType, $itemName)
    {
        if (in_array($itemType, self::$itemTypes)) {
            return 'The trello '.$itemType.' named '.$itemName.' could not be found.';
        }

        throw new \Exception('The declare Trello item type does not match any known type.');
    }

    /**
     * @return string
     */
    public function getItemName()
    {
        return $this->itemName;
    }

    /**
     * @return string
     */
    public function getItemType()
    {
        return $this->itemType;
    }
}
