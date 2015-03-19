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
     * @param Token $token
     * @return int
     */
    public function extractActualValue(Token $token)
    {
        return $token->getType();
    }
}
