<?php namespace Helstern\Nomsky\Tokens\TokenMatch;

use Helstern\Nomsky\Text\TextReader;

interface CompositeTextReaderTokenMatcher
{
    /**
     * @return array|TokenMatcher[]
     */
    public function getTokenMatchersList();

    /**
     * @param TextReader $reader
     * @return TokenMatch|null
     */
    public function match(TextReader $reader);
}
