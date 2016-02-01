<?php namespace Helstern\Nomsky\Tokens\DeprecatedTokenMatch;

use Helstern\Nomsky\Text\String\StringMatch;
use Helstern\Nomsky\Text\TextMatch;
use Helstern\Nomsky\Tokens\DeprecatedTokenPattern\TokenPattern;

class PrefixMatcher implements TokenStringMatcher
{
    /** @var TokenPattern  */
    protected $tokenPattern;

    public function __construct (TokenPattern $tokenPattern)
    {
        $this->tokenPattern = $tokenPattern;
    }

    /**
     * @param string $string
     * @return TextMatch
     */
    public function match($string)
    {
        $prefix = $this->tokenPattern->getTokenPattern();
        if (0 === mb_strpos($string, $prefix, 0, 'UTF-8')) {
            $matchedText = mb_substr($string, 0, mb_strlen($prefix));
            $stringMatch = $this->createTextMatch($matchedText);
            return $stringMatch;
        }

        return null;
    }

    /**
     * @param $matchedText
     * @return StringMatch
     */
    protected function createTextMatch($matchedText)
    {
        $match = new StringMatch($matchedText);
        return $match;
    }

    /**
     * @return TokenPattern
     */
    public function getTokenPattern()
    {
        return $this->tokenPattern;
    }
}
