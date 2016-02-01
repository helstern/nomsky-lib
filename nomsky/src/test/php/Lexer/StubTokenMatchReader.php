<?php namespace Helstern\Nomsky\Lexer;

class StubTokenMatchReader implements TokenMatchReader
{
    /**
     * @var TokenMatch
     */
    private $match;

    public function __construct(TokenMatch $match)
    {
        $this->match = $match;
    }

    /**
     * @param TextReader $reader
     *
     * @return TokenMatch
     */
    public function read(TextReader $reader)
    {
        return $this->match;
    }
}
