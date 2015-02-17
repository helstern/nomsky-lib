<?php namespace Helstern\Nomsky\Tokens\TokenStream;

use Helstern\Nomsky\Text\TextPosition;
use Helstern\Nomsky\TextMatch\RegexMatchStream;
use Helstern\Nomsky\Tokens\Token;
use Helstern\Nomsky\Tokens\TokenTypeEnum;

class RegexTokenStream implements TokenStream
{
    /** @var RegexMatchStream */
    protected $regexStream;

    /** @var CharacterSource */
    protected $characterSource;

    /** @var Token */
    protected $eofToken;

    /**
     * @param RegexMatchStream $regexStream
     * @param CharacterSource $characterSource
     */
    public function __construct(RegexMatchStream $regexStream, CharacterSource $characterSource)
    {
        $this->regexStream = $regexStream;
        $this->characterSource = $characterSource;
        $this->token = $this->createToken($regexStream->current(), $regexStream->position());
    }

    /**
     * @return CharacterSource
     */
    public function getSource()
    {
        return $this->characterSource;
    }

    /**
     * @return Token
     */
    public function current()
    {
        if (!is_null($this->eofToken)) {
            return $this->eofToken;
        } else {
            return $this->token;
        }
    }

    /**
     * @return bool
     */
    public function valid()
    {
        return !is_null($this->eofToken);
    }

    /**
     * @return bool
     */
    public function next()
    {
        if (!is_null($this->eofToken)) {
            return false;
        }

        if ($this->regexStream->next()) {
            $this->token = $this->createToken($this->regexStream->current(), $this->regexStream->position());;
            return true;
        }

        $this->eofToken = $this->createEOFToken();
        $this->token = null;

        return false;
    }

    /**
     * @param array $regexMatch
     * @param TextPosition $tokenPosition
     * @return Token
     */
    protected function createToken(array $regexMatch, TextPosition $tokenPosition)
    {
        /** @var int $tokenType */
        $tokenType = null;
        /** @var array $matchInfo */
        $matchInfo = array();
        foreach ($regexMatch as $patternKey => $matchInfo) {
            $tokenMatchInfo = array();
            if (preg_match('#^pattern(\d+)$#', $patternKey, $tokenMatchInfo)) {
                $tokenType = $tokenMatchInfo[1];
                break;
            }
        }

        $tokenValue       = $matchInfo[0];
        $token = new Token($tokenType, $tokenValue, $tokenPosition);

        return $token;
    }

    /**
     * @return Token
     */
    protected function createEOFToken()
    {
        $tokenType = TokenTypeEnum::TYPE_EOF;
        $tokenValue = '';
        $tokenPosition = new TextPosition(0, 0, 0);

        return new Token($tokenType, $tokenValue, $tokenPosition);
    }
}
