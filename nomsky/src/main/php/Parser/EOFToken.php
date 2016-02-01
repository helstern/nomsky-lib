<?php namespace Helstern\Nomsky\Parser;

use Helstern\Nomsky\Tokens\DefaultTokenTypesEnum;

class EOFToken implements Token
{
    /**
     * @var TokenPosition
     */
    private $position;

    /**
     * @param TokenPosition $position
     */
    public function __construct(TokenPosition $position)
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
     * @return TokenPosition
     */
    public function getPosition()
    {
        return $this->position;
    }
}
