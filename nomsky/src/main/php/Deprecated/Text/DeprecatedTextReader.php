<?php namespace Helstern\Nomsky\Text;

interface DeprecatedTextReader
{
    /**
     * Reads one character
     *
     * @return string|null
     */
    public function readCharacter();

    /**
     * Reads a token match
     *
     * @param DeprecatedTextMatcher $matcher
     *
     * @return TextMatch|null
     */
    public function readTextMatch(DeprecatedTextMatcher $matcher);

    /**
     * @param int $bytes
     * @return bool
     */
    public function skip($bytes);
}
