<?php namespace Helstern\Nomsky\Tokens\DeprecatedTokenPattern;

abstract class AbstractRegexTokenPattern implements TokenPattern
{
    protected $tokenType;

    public function getTokenType()
    {
        return $this->tokenType;
    }
}
