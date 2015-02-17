<?php namespace Helstern\Nomsky\Text\String;

use Helstern\Nomsky\Text\TextMatcher;
use Helstern\Nomsky\Text\TextReader;
use Helstern\Nomsky\Text\TextSource;

class StringReader implements TextReader
{
    /** @var TextSource  */
    protected $source;

    /** @var string */
    protected $readText;

    protected $remainingText;

    public function __construct(TextSource $source)
    {
        $this->source = $source;
        $text = $source->retrieveText();

        if (empty($text)) {
            $this->remainingText = null;
        } else {
            $this->remainingText = $text;
        }
    }

    /**
     * @return TextSource
     */
    public function getSource()
    {
        return $this->source;
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
