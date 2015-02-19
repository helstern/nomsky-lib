<?php namespace Helstern\Nomsky\Tokens\TokenMatch;

use Helstern\Nomsky\Text\String\StringMatch;
use Helstern\Nomsky\Tokens\TokenPattern\TokenPattern;

class AnchoredPcreMatcher implements TokenStringMatcher
{
    /** @var TokenPattern  */
    protected $tokenPattern;

    public function __construct (TokenPattern $tokenPattern)
    {
        $this->tokenPattern = $tokenPattern;
    }

    public function getTokenPattern()
    {
        return $this->tokenPattern;
    }

    public function match($string)
    {
        $pattern = $this->buildAnchoredPattern();

        $matches = [];
        $nrMatches = preg_match($pattern, $string, $matches);
        if ($nrMatches > 0) {
            $tokenMatch = $this->createTokenMatch($matches);
            return $tokenMatch;
        }

        return null;
    }

    /**
     * @param array $pregMatch
     * @return TokenMatch
     */
    protected function createTokenMatch(array $pregMatch)
    {
        $matchedText = $pregMatch[0];

        $simpleMatch = new StringMatch($matchedText);
        $match = new TokenMatch($simpleMatch, $this->tokenPattern);
        return $match;
    }

    protected function buildAnchoredPattern()
    {
        $patternString = '^' . $this->tokenPattern->getTokenPattern();
        $patternString = '#' . $patternString . '#';

        $patternString .= 'm'; //PCRE_MULTILINE
        $patternString .= 's'; //PCRE_DOTALL
        $patternString .= 'u'; //utf-8

        return $patternString;
    }
}
