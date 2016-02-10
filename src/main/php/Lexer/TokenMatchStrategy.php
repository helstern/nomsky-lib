<?php namespace Helstern\Nomsky\Lexer;

interface TokenMatchStrategy
{
    /**
     * @param TextReader $reader
     * @param array|TokenMatchReader[] $tokenMatchersList
     *
     * @return TokenMatch|null
     */
    public function match(TextReader $reader, array $tokenMatchersList);
}
