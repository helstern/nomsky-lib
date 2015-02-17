<?php namespace Helstern\Nomsky\Tokens\TokenMatch;

use Helstern\Nomsky\Text\TextMatcher;
use Helstern\Nomsky\Tokens\TokenPattern\TokenPattern;

interface TokenMatcher extends TextMatcher
{
    /**
     * @return TokenPattern
     */
    public function getTokenPattern();

    /**
     * @param string $text
     * @return TokenMatch
     */
    public function match($text);
}
