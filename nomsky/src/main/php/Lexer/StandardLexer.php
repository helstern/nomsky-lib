<?php namespace Helstern\Nomsky\Lexer;

use Helstern\Nomsky\Parser\Lexer;
use Helstern\Nomsky\Parser\TokenPosition;
use Helstern\Nomsky\Tokens\StringToken;

class StandardLexer implements Lexer
{
    /** @var TokenMatchStrategy */
    private $matchStrategy;

    /** @var TextReader */
    private $textReader;

    /** @var array */
    private $tokenMatchers;

    /** @var TextMatcher */
    private $whitespaceMatcher;

    /** @var StringToken */
    private $token;

    /** @var StringToken */
    private $peekToken;

    /** @var boolean */
    private $readEnd = false;

    /** @var boolean */
    private $peekConsume = false;

    /**
     * @param array|TokenMatchReader[] $tokenMatchers
     * @param TokenMatchStrategy $matchStrategy
     * @param TextReader $textReader
     * @param TextMatcher $whitespaceMatcher
     */
    public function __construct(
        array $tokenMatchers,
        TokenMatchStrategy $matchStrategy,
        TextReader $textReader,
        TextMatcher $whitespaceMatcher
    ) {
        $this->tokenMatchers = $tokenMatchers;
        $this->matchStrategy = $matchStrategy;
        $this->textReader = $textReader;
        $this->whitespaceMatcher = $whitespaceMatcher;

        $this->token = $this->readFirstToken();
    }


    /**
     * @return null|StringToken
     */
    public function currentToken()
    {
        return $this->token;
    }

    /**
     * @return boolean
     */
    public function nextToken()
    {
        if (! is_null($this->peekToken)) {
            $this->consumePeekToken();
            return true;
        }

        if ($this->readEnd) {
            return false;
        }

        // reach end
        $nextChar = $this->textReader->readCharacter();
        if (is_null($nextChar)) {
            $this->token = null;
            $this->readEnd = true;
            return false;
        }

        //read next token
        $token = $this->readNextToken();

        if (is_null($token)) {
            $this->token = null;
            $this->readEnd = true;
            return false;
        }

        $this->token = $token;
        return true;
    }

    /**
     * @return StringToken
     * @throws \Exception
     * @throws \RuntimeException
     */
    public function peekToken()
    {
        if (false == $this->peekConsume) {
            return $this->peekToken;
        }

        if ($this->readEnd) {
            throw new \Exception();
        }

        $nextChar = $this->textReader->readCharacter();
        if (is_null($nextChar)) {
            throw new \RuntimeException('Can not peek over EOF');
        }

        //read next token
        $token = $this->readNextToken();
        $this->peekToken = $token;

        $this->peekConsume = false;
        return $token;
    }

    private function consumePeekToken()
    {
        $this->token = $this->peekToken;
        $this->peekToken = null;
        $this->peekConsume = true;
    }

    /**
     * @return StringToken|null
     */
    private function readFirstToken()
    {
        $startPosition = new TokenPosition(0, 1, 1);
        $token = $this->readToken($startPosition);

        if (is_null($token)) {
            $this->readEnd = true;
        }
        return $token;
    }

    /**
     * @return StringToken|null
     */
    private function readNextToken()
    {
        //read next token
        $position = $this->token->getPosition();
        $token = $this->readToken($position);

        return $token;
    }

    /**
     * @param TokenPosition $from
     *
     * @return StringToken|null
     */
    private function readToken(TokenPosition $from)
    {
        /** @var TokenPosition $position */
        $position = null;

        $whitespaceMatch = $this->matchWhitespace();
        if (is_null($whitespaceMatch)) {
            $position = $from;
        } else {
            $position = $this->calculatePosition($whitespaceMatch, $from);
            $advanceBytes = $whitespaceMatch->getByteLength();
            //advance the reader
            $this->textReader->skip($advanceBytes);
        }

        $tokenMatch = $this->matchToken();
        if (is_null($tokenMatch)) {
            return null;
        }

        $position = $this->calculatePosition($tokenMatch, $position);
        $advanceBytes = $tokenMatch->getByteLength();
        //advance the reader
        $this->textReader->skip($advanceBytes);

        $token = $this->createToken($tokenMatch, $position);
        return $token;
    }

    /**
     * @param TokenMatch $tokenMatch
     * @param TokenPosition $tokenPosition
     *
*@return StringToken
     */
    private function createToken(TokenMatch $tokenMatch, TokenPosition $tokenPosition)
    {
        $tokenType = $tokenMatch->getTokenType();
        $tokenValue = $tokenMatch->getText();

        $token = new StringToken($tokenType, $tokenValue, $tokenPosition);
        return $token;
    }


    /**
     * @return TokenMatch
     */
    private function matchToken()
    {
        $match = $this->matchStrategy->match($this->textReader, $this->tokenMatchers);
        return $match;
    }

    /**
     * @return WhitespaceMatch|null
     */
    private function matchWhitespace()
    {
        $whitespace = $this->textReader->readTextMatch($this->whitespaceMatcher);
        if ($whitespace == '') {
            return null;
        }

        $match = new WhitespaceMatch($whitespace);
        return $match;
    }

    /**
     * @param TokenMatch $textMatch
     *
     * @param TokenPosition $previous
     *
     * @return TokenPosition
     */
    private function calculatePosition(TokenMatch $textMatch, TokenPosition $previous)
    {
        $offsetPosition = $this->calculatePositionOffset($textMatch);
        $nextPosition   = $previous->offsetRight($offsetPosition);

        return $nextPosition;
    }

    /**
     * @param TokenMatch $textMatch
     *
*@return TokenPosition
     */
    private function calculatePositionOffset(TokenMatch $textMatch)
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
        $offsetPosition = new TokenPosition($offsetByte, $startColumn + $offsetColumn, $offsetLines);
        return $offsetPosition;
    }
}
