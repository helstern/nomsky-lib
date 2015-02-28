<?php namespace Helstern\Nomsky\Tokens;

use Helstern\Nomsky\Text\TextPosition;

class Token {

    /** @var int */
    protected $type;

    /** @var string */
    protected $text;

    /** @var TextPosition */
    protected $position;

    public function __construct($type, $text, TextPosition $firstCharacterPosition)
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
     * @return TextPosition
     */
    public function getPosition()
    {
        return $this->position;
    }

    /**
     * @param TokenEqualityTestStrategy $testStrategy
     * @return bool
     */
    public function testEquality(TokenEqualityTestStrategy $testStrategy)
    {
        if ($testStrategy->getExpectedValue() == $testStrategy->extractActualValue($this)) {
            return true;
        }

        return false;
    }
}
