<?php namespace Helstern\Nomsky\Text;

interface TextMatcher
{
    /**
     * @param string $text
     * @return TextMatch
     */
    public function match($text);
}
