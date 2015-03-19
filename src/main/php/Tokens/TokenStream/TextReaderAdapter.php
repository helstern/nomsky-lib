<?php namespace Helstern\Nomsky\Tokens\TokenStream;

use Helstern\Nomsky\Lexer\TokenStream;
use Helstern\Nomsky\Text\TextPosition;
use Helstern\Nomsky\Text\TextReader;
use Helstern\Nomsky\Text\TextMatch;
use Helstern\Nomsky\Text\String\StringMatch;

use Helstern\Nomsky\Tokens\Token;
use Helstern\Nomsky\Tokens\TokenDefinition;
use Helstern\Nomsky\Tokens\TokenMatch\TokenMatch;

class TextReaderAdapter implements TokenStream
{
    /** @var TextReader */
    protected $textReader;

    /** @var CompositeTokenStringMatcher  */
    protected $tokenMatchReader;

    /** @var Token */
    protected $token;

    /** @var TextMatch */
    protected $tokenMatch;

    /** @var Token */
    protected $eofToken;

    /** @var TokenDefinition */
    protected $eofTokenDefinition;

    /**
     * @param TextReader $sourceReader
     * @param CompositeTokenStringMatcher $nextTokenReader
     * @param TokenDefinition $eofTokenDefinition
     */
    public function __construct(
        TextReader $sourceReader,
        CompositeTokenStringMatcher $nextTokenReader,
        TokenDefinition $eofTokenDefinition
    ) {
        $this->textReader = $sourceReader;
        $this->tokenMatchReader = $nextTokenReader;
        $this->eofTokenDefinition = $eofTokenDefinition;

        $previousPosition = new TextPosition(0, 1, 1);
        $token = $this->readNextToken($previousPosition);
        $this->token = $token;
    }

    /**
     * @param TextPosition $previousPosition
     * @return Token
     */
    protected function readNextToken(TextPosition $previousPosition)
    {
        $whitespaceMatch = $this->matchWhitespace();

        $tokenMatch = $this->tokenMatchReader->match($this->textReader);
        $this->tokenMatch = $tokenMatch;

        if (is_null($tokenMatch)) {
            return $this->createEOFToken();
        }

        $offsetPosition = $this->calculateTextMatchOffset($whitespaceMatch);
        $tokenPosition = $previousPosition->offsetRight($offsetPosition);
        $token = $this->createToken($tokenMatch, $tokenPosition);

        $this->textReader->skip($tokenMatch->getByteLength());

        return $token;
    }

    /**
     * @param \Helstern\Nomsky\Text\TextMatch $textMatch
     * @return TextPosition
     */
    protected function calculateTextMatchOffset(TextMatch $textMatch)
    {
        $offsetTextMatch = $textMatch->getText();
        $offsetByte = $textMatch->getByteLength();

        $startColumn = 0;
        $offsetLines = (int) preg_match_all("#(\r\n|\n|\r)#m", $offsetTextMatch, $newLineMatches, PREG_OFFSET_CAPTURE);
        if ($offsetLines > 0) {
            /** @var array $lastPregMatch */
            $lastPregMatch = end($newLineMatches);
            $lastMatchByteIndex = mb_strlen($lastPregMatch[0][0]) + $lastPregMatch[0][1];
            $offsetTextMatch = substr($offsetTextMatch, $lastMatchByteIndex);

            $startColumn = 1;
        }
        $offsetColumn = mb_strlen($offsetTextMatch, 'UTF-8');
        $offsetPosition = new TextPosition($offsetByte, $startColumn + $offsetColumn, $offsetLines);
        return $offsetPosition;
    }

    /**
     * @return StringMatch
     */
    protected function matchWhitespace()
    {
        $matchText = '';
        $char = $this->textReader->readCharacter();
        while (preg_match('#[[:space:]]|\r\n|\n|\r#m', $char)) {
            $matchText .= $char;
            $this->textReader->skip(strlen($char));

            $char = $this->textReader->readCharacter();
        }

        return new StringMatch($matchText);
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

        $nextPosition = $this->nextTextPosition();
        $nextToken = $this->readNextToken($nextPosition);

        if ($this->isEOFToken($nextToken)) {
            $this->eofToken = $nextToken;
            $this->token = null;

            return false;
        }

        $this->token = $nextToken;
        return true;
    }

    /**
     * @return TextPosition
     */
    protected function nextTextPosition()
    {
        $offsetPosition = $this->calculateTextMatchOffset($this->tokenMatch);
        $tokenPosition  = $this->token->getPosition();
        $nextPosition   = $tokenPosition->offsetRight($offsetPosition);

        return $nextPosition;
    }

    /**
     * @param Token $token
     * @return bool
     */
    protected function isEOFToken(Token $token)
    {
        if ($token->getType() == $this->eofTokenDefinition->getType()) {
            return true;
        }

        return false;
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
        $tokenType = $this->eofTokenDefinition->getType();
        $tokenValue = $this->eofTokenDefinition->getValue();
        $tokenPosition = new TextPosition(0, 0, 0);

        return new Token($tokenType, $tokenValue, $tokenPosition);
    }
}