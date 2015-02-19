<?php namespace Helstern\Nomsky\Tokens\TokenStream;

use Helstern\Nomsky\Text\TextPosition;
use Helstern\Nomsky\Text\TextReader;
use Helstern\Nomsky\Text\TextMatch;
use Helstern\Nomsky\Text\String\StringMatch;

use Helstern\Nomsky\Text\TextSource;
use Helstern\Nomsky\Tokens\Token;
use Helstern\Nomsky\Tokens\TokenMatch\TokenMatch;

use Helstern\Nomsky\Lexer\NomskyTokenTypeEnum;

class MatcherListTokenStream implements TokenStream
{
    /** @var TextReader */
    protected $sourceReader;

    /** @var TokenStringMatcherListAdapter  */
    protected $tokenMatchReader;

    /** @var Token */
    protected $token;

    /** @var Token */
    protected $eofToken;

    /**
     * @param TextReader $sourceReader
     * @param TokenStringMatcherListAdapter $nextTokenReader
     */
    public function __construct(TextReader $sourceReader, TokenStringMatcherListAdapter $nextTokenReader)
    {
        $this->sourceReader = $sourceReader;
        $this->tokenMatchReader = $nextTokenReader;

        $previousPosition = new TextPosition(0, 0, 0);
        $this->token = $this->readNextToken($previousPosition);
    }

    /**
     * @param TextPosition $previousPosition
     * @return Token
     */
    protected function readNextToken(TextPosition $previousPosition)
    {
        $whitespaceMatch = $this->matchWhitespace();

        $tokenMatch = $this->tokenMatchReader->match($this->sourceReader);
        if (is_null($tokenMatch)) {
            return $this->createEOFToken();
        }

        $this->sourceReader->skip($tokenMatch->getByteLength());

        $offsetPosition = $this->calculateOffsetPosition($whitespaceMatch, $tokenMatch);
        $tokenPosition = $previousPosition->offsetRight($offsetPosition);

        $token = $this->createToken($tokenMatch, $tokenPosition);
        return $token;
    }

    /**
     * @param TextMatch $whitespaceMatch
     * @param TextMatch $tokenMatch
     * @return TextPosition
     */
    protected function calculateOffsetPosition(TextMatch $whitespaceMatch, TextMatch $tokenMatch)
    {
        $offsetTextMatch = $whitespaceMatch->getText() . $tokenMatch->getText();
        $offsetByte = $whitespaceMatch->getByteLength() + $tokenMatch->getByteLength();

        $offsetLines = (int)preg_match("#\r\n|\n|\r#m", $offsetTextMatch, $newLineMatches, PREG_OFFSET_CAPTURE);
        if ($offsetLines > 0) {
            $lastMatchByteIndex = end($newLineMatches)[1];
            $offsetTextMatch = substr($offsetTextMatch, $lastMatchByteIndex);
        }
        $offsetColumn = mb_strlen($offsetTextMatch, 'UTF-8');
        $offsetPosition = new TextPosition($offsetByte, $offsetColumn, $offsetLines);
        return $offsetPosition;
    }

    /**
     * @return StringMatch
     */
    protected function matchWhitespace()
    {
        $matchText = '';
        $char = $this->sourceReader->readCharacter();
        while (preg_match('[:space:]', $char)) {
            $matchText .= $char;
            $this->sourceReader->skip(strlen($char));
        }

        return new StringMatch($matchText);
    }

    /**
     * @return TextSource
     */
    public function getSource()
    {
        return $this->sourceReader->getSource();
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

        $previousPosition = $this->token->getPosition();
        $nextToken = $this->readNextToken($previousPosition);
        if ($nextToken->getType() == NomskyTokenTypeEnum::TYPE_EOF) {
            $this->eofToken = $nextToken;
            $this->token = null;

            return false;
        }

        $this->token = $nextToken;
        return true;
    }

    /**
     * @param TokenMatch $tokenMatch
     * @param TextPosition $tokenPosition
     * @return Token
     */
    protected function createToken(TokenMatch $tokenMatch, TextPosition $tokenPosition)
    {
        $tokenType = $tokenMatch->getTokenType();
        $tokenValue = $tokenMatch->getText();

        $token = new Token($tokenType, $tokenValue, $tokenPosition);
        return $token;
    }

    /**
     * @return Token
     */
    protected function createEOFToken()
    {
        $tokenType = NomskyTokenTypeEnum::TYPE_EOF;
        $tokenValue = '';
        $tokenPosition = new TextPosition(0, 0, 0);

        return new Token($tokenType, $tokenValue, $tokenPosition);
    }
}
