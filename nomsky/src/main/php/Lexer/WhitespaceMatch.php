<?php namespace Helstern\Nomsky\Lexer;

class WhitespaceMatch implements TokenMatch
{
    /** @var  string */
    protected $text;

    /** @var int  */
    protected $charLength;

    /** @var int */
    protected $byteLength;

    /**
     * @param string $matchedText
     * @param string $encoding
     */
    public function __construct($matchedText, $encoding = 'UTF-8')
    {
        $this->text = $matchedText;
        $this->charLength = mb_strlen($matchedText, $encoding);
        $this->byteLength = strlen($matchedText);
    }

    /**
     * @return string
     */
    public function getTokenType()
    {
        return ' ';
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
