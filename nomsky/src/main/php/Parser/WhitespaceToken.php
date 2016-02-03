<?php namespace Helstern\Nomsky\Parser;

use Helstern\Nomsky\Tokens\DefaultTokenTypesEnum;

class WhitespaceToken implements Token
{
    /**
     * @var string
     */
    private $value;

    /**
     * @var CharPosition
     */
    private $position;

    /**
     * @param CharPosition $position
     */
    public function __construct($value, CharPosition $position)
    {
        $this->value = $value;
        $this->position = $position;
    }

    /**
     * @return string
     */
    public function getType()
    {
        return DefaultTokenTypesEnum::ENUM_WS;
    }

    /**
     * @return string
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * @return CharPosition
     */
    public function getPosition()
    {
        return $this->position;
    }
}
