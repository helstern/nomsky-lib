<?php namespace Helstern\Nomsky\Parser;

use Helstern\Nomsky\Tokens\Token;

interface Lexer
{
    /**
     * @return null|Token
     */
    public function currentToken();

    /**
     * @return boolean
     */
    public function nextToken();

    /**
     * @return Token
     * @throw \Exception
     */
    public function peekToken();
}
