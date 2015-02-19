<?php namespace Helstern\Nomsky\Tokens\TokenMatch;

use Helstern\Nomsky\Text\StringMatcher;
use Helstern\Nomsky\Tokens\TokenPattern\TokenPattern;

interface TokenStringMatcher extends StringMatcher
{
    /**
     * @return TokenPattern
     */
    public function getTokenPattern();
}
