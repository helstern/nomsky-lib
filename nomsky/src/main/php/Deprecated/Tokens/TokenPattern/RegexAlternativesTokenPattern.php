<?php namespace Helstern\Nomsky\Tokens\DeprecatedTokenPattern;

class RegexAlternativesTokenPattern extends AbstractRegexTokenPattern
{
    /** @var array */
    protected $alternatives;

    /**
     * @param int $tokenType
     * @param array $alternatives
     */
    public function __construct($tokenType, array $alternatives)
    {
        $this->tokenType = $tokenType;

        $this->alternatives = $alternatives;
    }

    /**
     * @return array
     */
    public function getAlternatives()
    {
        return $this->alternatives;
    }

    public function getTokenPattern()
    {
        return implode('|', $this->alternatives);
    }
}
