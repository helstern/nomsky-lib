<?php namespace Helstern\Nomsky\Lexer\TokenStream;

use Helstern\Nomsky\Text\TextMatch;
use Helstern\Nomsky\Text\DeprecatedTextReader;

use Helstern\Nomsky\Tokens\DeprecatedTokenMatch\TokenMatch;
use Helstern\Nomsky\Tokens\DeprecatedTokenMatch\TokenStringMatcher;
use Helstern\Nomsky\Tokens\DeprecatedTokenStream\DeprecatedCompositeTokenStringMatcher;

class DeprecatedFirstMatchCompositeMatcher implements DeprecatedCompositeTokenStringMatcher
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
     * @param DeprecatedTextReader $reader
     *
*@return TokenMatch|null
     */
    public function match(DeprecatedTextReader $reader)
    {
        $oneCharacterAhead = $reader->readCharacter();
        if (is_null($oneCharacterAhead)) {
            return null;
        }

        /** @var TextMatch $tokenMatch */
        $nextMatch = null;
        /** @var TokenStringMatcher $matcher */
        $matcher = null;
        foreach ($this->tokenMatchersList as $matcher) {
            $nextMatch = $reader->readTextMatch($matcher);
            if (is_null($nextMatch)) {
                continue;
            }
            break;
        }

        if (is_null($nextMatch)) {
            return null;
        }
        $tokenMatch = $this->createTokenMatch($nextMatch, $matcher);
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
}
