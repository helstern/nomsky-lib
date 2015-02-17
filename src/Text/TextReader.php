<?php namespace Helstern\Nomsky\Text;


interface TextReader
{
    /**
     * @return TextSource
     */
    public function getSource();

    /**
     * Reads one character
     *
     * @return string|null
     */
    public function readCharacter();

    /**
     * Reads a token match
     *
     * @param TextMatcher $matcher
     * @return TextMatch|null
     */
    public function readTextMatch(TextMatcher $matcher);

    /**
     * @param int $bytes
     * @return bool
     */
    public function skip($bytes);
}
