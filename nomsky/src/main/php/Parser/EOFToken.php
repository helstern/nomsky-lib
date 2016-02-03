<?php namespace Helstern\Nomsky\Parser;

use Helstern\Nomsky\Tokens\DefaultTokenTypesEnum;

class EOFToken implements Token
{
    /**
     * @var CharPosition
     */
    private $position;

    /**
     * @param CharPosition $position
     */
    public function __construct(CharPosition $position)
    {
        $this->position = $position;
    }

    /**
     * @return string
     */
    public function getType()
    {
        return DefaultTokenTypesEnum::ENUM_EOF;
    }

    /**
     * @return string
     */
    public function getValue()
    {
        return '';
    }

    /**
     * @return CharPosition
     */
    public function getPosition()
    {
        return $this->position;
    }
}
