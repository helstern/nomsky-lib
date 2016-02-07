<?php namespace Helstern\Nomsky\Tokens;

class TokenTypeEqualityTestStrategy implements TokenEqualityTestStrategy
{
    /** @var int */
    protected $expectedTokenType;

    /**
     * @param int $type
     */
    public function __construct($type)
    {
        $this->expectedTokenType = $type;
    }

    /**
     * @return int
     */
    public function getExpectedValue()
    {
        return $this->expectedTokenType;
    }

    /**
     * @param StringToken $token
     *
*@return int
     */
    public function extractActualValue(StringToken $token)
    {
        return $token->getType();
    }
}
