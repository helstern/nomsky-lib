<?php namespace Helstern\Nomsky\Lexer;

interface TokenMatchReader
{
    /**
     *
     * @param TextReader $reader
     *
     * @return TokenMatch
     */
    public function read(TextReader $reader);
}
