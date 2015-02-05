<?php namespace Helstern\Nomsky\Tokens;

use Helstern\Nomsky\TextMatch\CharacterPosition;

class Token {

    /** @var int */
    protected $type;

    /** @var string */
    protected $text;

    /** @var CharacterPosition */
    protected $position;

    public function __construct($type, $text, CharacterPosition $firstCharacterPosition)
    {
        $this->type = $type;
        $this->text = $text;
        $this->position = $firstCharacterPosition;
    }

    /**
     * @return int
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @return string
     */
    public function getValue()
    {
        return $this->text;
    }

    /**
     * @return CharacterPosition
     */
    public function getPosition()
    {
        return $this->position;
    }
}
