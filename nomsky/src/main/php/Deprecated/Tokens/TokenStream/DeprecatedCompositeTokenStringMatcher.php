<?php namespace Helstern\Nomsky\Tokens\DeprecatedTokenStream;

use Helstern\Nomsky\Text\DeprecatedTextReader;
use Helstern\Nomsky\Tokens\DeprecatedTokenMatch\TokenMatch;
use Helstern\Nomsky\Tokens\DeprecatedTokenMatch\TokenStringMatcher;

interface DeprecatedCompositeTokenStringMatcher
{
    /**
     * @return array|TokenStringMatcher[]
     */
    public function getTokenMatchersList();

    /**
     * @param DeprecatedTextReader $reader
     *
*@return TokenMatch|null
     */
    public function match(DeprecatedTextReader $reader);
}

