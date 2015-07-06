<?php namespace Helstern\Nomsky\Text;

interface StringMatcher
{
    /**
     * @param string $string
     * @return TextMatch
     */
    public function match($string);
}
