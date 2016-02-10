<?php namespace Helstern\Nomsky\Lexer;

interface TextReader
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
     * @param TextMatcher $matcher
     *
     * @return string|null
     */
    public function readTextMatch(TextMatcher $matcher);

    /**
     * @param int $bytes
     * @return bool
     */
    public function skip($bytes);
}
