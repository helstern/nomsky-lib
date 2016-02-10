<?php namespace Helstern\Nomsky\Text;

use Helstern\Nomsky\Lexer\TokenMatch;

class TokenStringMatch implements TokenMatch
{
    /** @var string */
    private $tokenType;

    /** @var  string */
    protected $text;

    /** @var int  */
    protected $charLength;

    /** @var int */
    protected $byteLength;

    /**
     * @param $tokenType
     * @param string $matchedText
     * @param string $encoding
     */
    public function __construct($tokenType, $matchedText, $encoding = 'UTF-8')
    {
        $this->tokenType = $tokenType;
        $this->text = $matchedText;
        $this->charLength = mb_strlen($matchedText, $encoding);
        $this->byteLength = strlen($matchedText);
    }

    /**
     * @return string
     */
    public function getTokenType()
    {
        return $this->tokenType;
    }

    /**
     * @return string
     */
    public function getText()
    {
        return $this->text;
    }

    public function getCharLength()
    {
        return $this->charLength;
    }

    public function getByteLength()
    {
        return $this->byteLength;
    }
}
