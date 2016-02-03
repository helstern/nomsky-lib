<?php namespace Helstern\Nomsky\Lexer;

interface TokenMatch
{
    /**
     * @return string
     */
    public function getTokenType();

    /**
     * @return string
     */
    public function getText();

    /**
     * @return int
     */
    public function getCharLength();

    /**
     * @return int
     */
    public function getByteLength();
}
