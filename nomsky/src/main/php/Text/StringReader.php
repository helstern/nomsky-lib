<?php namespace Helstern\Nomsky\Text;

use Helstern\Nomsky\Lexer\TextMatcher;
use Helstern\Nomsky\Lexer\TextReader;

class StringReader implements TextReader
{
    /** @var string */
    protected $readText;

    /** @var string */
    protected $remainingText;

    /** @var int */
    protected $remainingLength;

    public function __construct($text)
    {
        if (empty($text)) {
            $this->remainingText = null;
            $this->remainingLength = 0;
        } else {
            $this->remainingText = $text;
            $this->remainingLength = strlen($text);
        }
    }

    public function readCharacter()
    {
        if (is_null($this->remainingText)) {
            return null;
        }

        $character = mb_substr($this->remainingText, 0, 1);
        return $character;
    }

    public function readTextMatch(TextMatcher $matcher)
    {
        if (is_null($this->remainingText)) {
            return null;
        }

        $textMatch = $matcher->match($this->remainingText);
        if (!is_null($textMatch)) {
            return $textMatch;
        }

        return null;
    }

    public function skip($bytes)
    {
        if ($this->remainingLength == 0) {
            throw new \DomainException('can not skip past end');
        }

        if ($this->remainingLength < $bytes) {
            throw new \DomainException('reader error');
        }

        if ($this->remainingLength == $bytes) {
            $this->readText .= $this->remainingText;
            $this->remainingText = null;
            $this->remainingLength = 0;
            return;
        }

        $skippedText = substr($this->remainingText, 0, $bytes);
        $this->readText .= $skippedText;

        $remainingText = substr($this->remainingText, $bytes);
        if ($remainingText === false) {
            throw new \DomainException('reader error');
        }
        $this->remainingText = $remainingText;
        $this->remainingLength = $this->remainingLength - $bytes;
    }
}
