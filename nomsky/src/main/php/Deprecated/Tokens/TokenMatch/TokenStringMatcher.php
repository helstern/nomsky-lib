<?php namespace Helstern\Nomsky\Tokens\DeprecatedTokenMatch;

use Helstern\Nomsky\Text\DeprecatedTextMatcher;
use Helstern\Nomsky\Tokens\DeprecatedTokenPattern\TokenPattern;

interface TokenStringMatcher extends DeprecatedTextMatcher
{
    /**
     * @return TokenPattern
     */
    public function getTokenPattern();
}
