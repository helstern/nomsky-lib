<?php namespace Helstern\Nomsky\Text;

interface DeprecatedTextMatcher
{
    /**
     * @param string $string
     * @return TextMatch
     */
    public function match($string);
}
