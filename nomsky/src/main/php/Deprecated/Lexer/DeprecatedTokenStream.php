<?php namespace Helstern\Nomsky\Lexer;

use Helstern\Nomsky\Tokens\StringToken;

interface DeprecatedTokenStream
{
    /**
     * @return StringToken
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
