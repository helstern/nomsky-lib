<?php namespace Helstern\Nomsky\Parser;

use Helstern\Nomsky\Tokens\StringToken;

interface Lexer
{
    /**
     * @return null|StringToken
     */
    public function currentToken();

    /**
     * @return boolean
     */
    public function nextToken();

    /**
     * @return StringToken
     * @throw \Exception
     */
    public function peekToken();
}
