<?php namespace Helstern\Nomsky\Tokens\DeprecatedTokenPattern;

class RegexStringTokenPattern extends AbstractRegexTokenPattern
{
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

    public function getTokenPattern()
    {
        return $this->textPattern;
    }

}
