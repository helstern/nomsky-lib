<?php namespace Helstern\Nomsky\Text;

use Helstern\Nomsky\Lexer\TextMatcher;
use Helstern\Nomsky\Lexer\TextReader;

class StringReader implements TextReader
{
    /** @var string */
    protected $readText;

    protected $remainingText;

    public function __construct($text)
    {
        if (empty($text)) {
            $this->remainingText = null;
        } else {
            $this->remainingText = $text;
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
        $skippedText = substr($this->remainingText, 0, $bytes);
        $this->readText .= $skippedText;

        $this->remainingText = substr($this->remainingText, $bytes);
    }
}
