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

    /**
     * @param string $string
     * @return StringMatch
     */
    public function match($string)
    {
        $pattern = $this->buildAnchoredPattern();

        $matches = [];
        $nrMatches = preg_match($pattern, $string, $matches, PREG_OFFSET_CAPTURE);
        if ($nrMatches > 0) {
            $tokenMatch = $this->createTextMatch($matches, PREG_OFFSET_CAPTURE);
            return $tokenMatch;
        }

        return null;
    }

    /**
     * @param array $pregMatch
     * @param null|int $pregMatchFormat
     * @return StringMatch
     */
    protected function createTextMatch(array $pregMatch, $pregMatchFormat = 0)
    {
        if ($pregMatchFormat == PREG_OFFSET_CAPTURE) {
            $matchedText = $pregMatch[0][0];
        } else {
            $matchedText = $pregMatch[0];
        }


        $match = new StringMatch($matchedText);
        return $match;
    }

    protected function buildAnchoredPattern()
    {
        $patternString = '^' . $this->tokenPattern->getTokenPattern();
        $patternString = '#' . $patternString . '#';

        $modifiers = 's'; //PCRE_DOTALL
        $modifiers .= 'u'; //utf-8
        //disable PCRE_MULTILINE because it impacts anchoring
        //$modifiers .= 'm'; //PCRE_MULTILINE

        return $patternString.$modifiers;
    }
}
