<?php namespace Helstern\Nomsky\Lexer\TokenStream;

use Helstern\Nomsky\Text\TextMatch;
use Helstern\Nomsky\Text\TextReader;
use Helstern\Nomsky\Text\String\StringMatch;

use Helstern\Nomsky\Tokens\TokenMatch\TokenMatch;
use Helstern\Nomsky\Tokens\TokenMatch\TokenStringMatcher;
use Helstern\Nomsky\Tokens\TokenStream\CompositeTokenStringMatcher;

class LongestMatchCompositeMatcher implements CompositeTokenStringMatcher
{
    /** @var array|TokenStringMatcher[] */
    protected $tokenMatchersList;

    /**
     * @param array|TokenStringMatcher[] $tokenMatchersList
     */
    public function __construct(array $tokenMatchersList)
    {
        $this->tokenMatchersList = $tokenMatchersList;
    }

    /**
     * @return array|TokenStringMatcher[]
     */
    public function getTokenMatchersList()
    {
        return $this->tokenMatchersList;
    }

    /**
     * @param TextReader $reader
     * @return TokenMatch|null
     */
    public function match(TextReader $reader)
    {
        $oneCharacterAhead = $reader->readCharacter();
        if (is_null($oneCharacterAhead)) {
            return null;
        }

        /** @var StringMatch $longestMatch */
        $longestMatch = new StringMatch('');
        /** @var array|TokenMatch[] $equallyLongTokenMatches */
        $equallyLongTokenMatches = null;

        foreach ($this->tokenMatchersList as $matcher) {
            $nextMatch = $reader->readTextMatch($matcher);
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

            $tokenMatch = $this->createTokenMatch($nextMatch, $matcher);
            $equallyLongTokenMatches[] = $tokenMatch;
        }

        if (0 == $longestMatch->getCharLength()) {
            return null;
        }

        //disregard the fact that there could be more than one longest matches
        $tokenMatch = reset($equallyLongTokenMatches);
        return $tokenMatch;
    }

    /**
     * @param TextMatch $textMatch
     * @param TokenStringMatcher $matchedBy
     * @return TokenMatch
     */
    protected function createTokenMatch(TextMatch $textMatch, TokenStringMatcher $matchedBy)
    {
        $tokenPattern = $matchedBy->getTokenPattern();
        return new TokenMatch($textMatch, $tokenPattern);
    }

    /**
     * @param TextMatch $match candidate match
     * @param TextMatch $longestMatch current longestMatch
     * @return TextMatch|null
     */
    protected function chooseLongestMatch(TextMatch $match, TextMatch $longestMatch)
    {
        if ($match->getCharLength() < $longestMatch->getCharLength()) {
            return $longestMatch;
        }

        if ($match->getCharLength() == $longestMatch->getCharLength()) {
            return null;
        }

        return $match;
    }
}
