<?php namespace Helstern\Nomsky\Tokens\TokenPattern;

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
