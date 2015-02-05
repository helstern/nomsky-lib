<?php namespace Helstern\Nomsky\Tokens\TokenPattern;

class RegexTokenPattern implements TokenPattern
{
    protected $tokenType;

    protected $textPattern;

    /**
     * @param int $tokenType
     * @param string $textPattern
     */
    public function __construct($tokenType, $textPattern)
    {
        $this->tokenType = $tokenType;

        $this->textPattern = $textPattern;
    }

    public function getTokenType()
    {
        return $this->tokenType;
    }

    public function getTokenPattern()
    {
        return $this->textPattern;
    }
}
