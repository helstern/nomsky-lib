<?php namespace Helstern\Nomsky\Tokens\TokenStream;

use Helstern\Nomsky\Text\TextReader;
use Helstern\Nomsky\Tokens\TokenMatch\TokenMatch;
use Helstern\Nomsky\Tokens\TokenMatch\TokenStringMatcher;

interface CompositeTokenStringMatcher
{
    /**
     * @return array|TokenStringMatcher[]
     */
    public function getTokenMatchersList();

    /**
     * @param TextReader $reader
     * @return TokenMatch|null
     */
    public function match(TextReader $reader);
}

