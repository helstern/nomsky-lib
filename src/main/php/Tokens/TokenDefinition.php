<?php namespace Helstern\Nomsky\Tokens;

use Helstern\Nomsky\Text\PcrePattern;

class TokenDefinition
{
    private $tokenType;

    private $pcrePattern;

    public function __construct($tokenType, PcrePattern $pattern)
    {
        $this->tokenType = $tokenType;
        $this->pcrePattern = $pattern;
    }

    public function getType()
    {
        return $this->tokenType;
    }

    /**
     * @return PcrePattern
     */
    public function getPattern()
    {
        return $this->pcrePattern;
    }
}
