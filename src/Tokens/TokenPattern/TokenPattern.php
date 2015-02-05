<?php namespace Helstern\Nomsky\Tokens\TokenStream;

interface TokenPattern
{
    /**
     * @return int
     */
    public function getTokenType();

    /**
     * @return string
     */
    public function getTokenPattern();
}
