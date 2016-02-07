<?php namespace Helstern\Nomsky\Tokens;

use Helstern\Nomsky\Parser\CharPosition;
use Helstern\Nomsky\Parser\Token;

class StringToken implements Token
{
    /** @var string */
    protected $type;

    /** @var string */
    protected $text;

    /** @var CharPosition */
    protected $position;

    /**
     * @param string $type
     * @param string $text
     * @param CharPosition $firstCharacterPosition
     */
    public function __construct($type, $text, CharPosition $firstCharacterPosition)
    {
        $this->type = $type;
        $this->text = $text;
        $this->position = $firstCharacterPosition;
    }

    public function getType()
    {
        return $this->type;
    }

    public function getValue()
    {
        return $this->text;
    }

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
