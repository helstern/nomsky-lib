<?php namespace Helstern\Nomsky\Text;

use Helstern\Nomsky\Lexer\TextMatch;

class StringMatch implements TextMatch
{
    /** @var  string */
    protected $text;

    /** @var int  */
    protected $charLength;

    /** @var int */
    protected $byteLength;

    /**
     * @param string $matchedText
     */
    public function __construct($matchedText)
    {
        $this->text = $matchedText;
        $this->charLength = mb_strlen($matchedText, 'UTF-8');
        $this->byteLength = strlen($matchedText);
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
