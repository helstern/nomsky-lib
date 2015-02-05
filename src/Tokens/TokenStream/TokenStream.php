<?php namespace Helstern\Nomsky\Tokens\TokenStream;

use Helstern\Nomsky\Tokens\Token;

interface TokenStream
{
    /**
     * @return CharacterSource
     */
    public function getSource();

    /**
     * @return Token
     */
    public function current();

    /**
     * @return bool
     */
    public function valid();

    /**
     * @return bool
     */
    public function next();
}
