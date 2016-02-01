<?php namespace Helstern\Nomsky\Tokens\DeprecatedTokenMatch;

use Helstern\Nomsky\Tokens\DeprecatedTokenPattern\TokenPattern;
use Helstern\Nomsky\Text\TextMatch;

class TokenMatch implements TextMatch
{
    /** @var TextMatch */
    protected $textMatch;

    /** @var TokenPattern  */
    protected $tokenPattern;

    public function __construct(TextMatch $textMatch, TokenPattern $tokenPattern)
    {
        $this->textMatch = $textMatch;
        $this->tokenPattern = $tokenPattern;
    }

    /**
     * @return string
     */
    public function getTokenType()
    {
        return $this->tokenPattern->getTokenType();
    }

    /**
     * @return string
     */
    public function getText()
    {
        return $this->textMatch->getText();
    }

    public function getCharLength()
    {
        return $this->textMatch->getCharLength();
    }

    public function getByteLength()
    {
        return $this->textMatch->getByteLength();
    }
}
