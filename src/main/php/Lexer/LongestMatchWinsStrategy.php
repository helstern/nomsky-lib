<?php namespace Helstern\Nomsky\Lexer;

class LongestMatchWinsStrategy implements TokenMatchStrategy
{
    /**
     * @var EmptyMatch
     */
    private $emptyTokenMatch;

    public function __construct()
    {
        $this->emptyTokenMatch = EmptyMatch::instance();
    }

    /**
     * @param TextReader $reader
     * @param array|TokenMatchReader[] $tokenMatchersList
     *
     * @return TokenMatch|null
     */
    public function match(TextReader $reader, array $tokenMatchersList)
    {
        //verify reader has not gone past EOF

        $longestMatch = $this->emptyTokenMatch;
        /** @var array|TokenMatch[] $equallyLongTokenMatches */
        $equallyLongTokenMatches = null;

        foreach ($tokenMatchersList as $matcher) {
            $nextMatch = $matcher->read($reader);
            if (is_null($nextMatch)) {
                continue;
            }

            $longestMatch = $this->chooseLongestMatch($nextMatch, $longestMatch);
            if ($longestMatch === $nextMatch) { //new longest match
                $equallyLongTokenMatches = [];
            } elseif (is_null($longestMatch)) { //same length match
                $longestMatch = $nextMatch;
            } else { //shorter match
                continue;
            }

            $equallyLongTokenMatches[] = $longestMatch;
        }

        if (0 == $longestMatch->getCharLength()) {
            return null;
        }

        //disregard the fact that there could be more than one longest matches
        $tokenMatch = reset($equallyLongTokenMatches);
        return $tokenMatch;
    }

    /**
     * @param TokenMatch $match candidate match
     * @param TokenMatch $longestMatch current longestMatch
     * @return TokenMatch|null
     */
    protected function chooseLongestMatch(TokenMatch $match, TokenMatch $longestMatch)
    {
        if ($match->getCharLength() == $longestMatch->getCharLength()) {
            return null;
        }

        if ($match->getCharLength() < $longestMatch->getCharLength()) {
            return $longestMatch;
        }

        return $match;
    }
}
