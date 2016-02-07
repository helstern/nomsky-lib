<?php namespace Helstern\Nomsky\Text;

use Helstern\Nomsky\Lexer\TextMatcher;
use Helstern\Nomsky\Lexer\TextReader;
use Helstern\Nomsky\Lexer\TokenMatch;
use Helstern\Nomsky\Lexer\TokenMatchReader;

class TokenMatchPcreReader implements TextMatcher, TokenMatchReader
{
    /**
     * @var string
     */
    private $tokenType;

    /**
     * @var PcreMatchOne
     */
    private $textMatcher;

    public function __construct($tokenType, PcreMatchOne $textMatcher)
    {
        $this->tokenType = $tokenType;
        $this->textMatcher = $textMatcher;
    }

    /**
     * @return string
     */
    public function getTokenType()
    {
        return $this->tokenType;
    }

    /**
     *
     * @param TextReader $reader
     *
     * @return TokenMatch|null
     */
    public function read(TextReader $reader)
    {
        $matchedText = $reader->readTextMatch($this);
        if (!is_null($matchedText)) {
            return new TokenStringMatch($this->tokenType, $matchedText);
        }

        return null;
    }

    /**
     * @param $string
     *
     * @return string
     */
    public function match($string)
    {
        $matchedText = $this->textMatcher->firstAtOffset($string, 0);
        return $matchedText;
    }
}
