<?php namespace Helstern\Nomsky\Lexer;

interface TextMatcher
{
    /**
     * @param string $string
     * @return string
     */
    public function match($string);
}
