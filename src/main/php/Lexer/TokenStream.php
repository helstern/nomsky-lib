<?php namespace Helstern\Nomsky\Lexer;

use Helstern\Nomsky\Tokens\Token;

interface TokenStream
{
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
