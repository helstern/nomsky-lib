<?php namespace Helstern\Nomsky\Tokens\DeprecatedTokenPattern;

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
