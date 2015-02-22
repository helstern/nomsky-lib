<?php namespace Helstern\Nomsky\Tokens\TokenPattern;

abstract class AbstractRegexTokenPattern implements TokenPattern
{
    protected $tokenType;

    public function getTokenType()
    {
        return $this->tokenType;
    }
}
