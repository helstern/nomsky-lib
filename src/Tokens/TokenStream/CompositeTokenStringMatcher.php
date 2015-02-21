<?php namespace Helstern\Nomsky\Tokens\TokenStream;

use Helstern\Nomsky\Text\TextSourceReader;
use Helstern\Nomsky\Tokens\TokenMatch\TokenMatch;
use Helstern\Nomsky\Tokens\TokenMatch\TokenStringMatcher;

interface CompositeTokenStringMatcher
{
    /**
     * @return array|TokenStringMatcher[]
     */
    public function getTokenMatchersList();

    /**
     * @param TextSourceReader $reader
     * @return TokenMatch|null
     */
    public function match(TextSourceReader $reader);
}

