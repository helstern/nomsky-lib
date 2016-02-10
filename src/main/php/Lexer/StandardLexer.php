<?php namespace Helstern\Nomsky\Lexer;

use Helstern\Nomsky\Parser\EOFToken;
use Helstern\Nomsky\Parser\Lexer;
use Helstern\Nomsky\Parser\CharPosition;
use Helstern\Nomsky\Tokens\StringToken;

/**
 * the lexer's current token is either a whitespace, a token or e-o-f
 * the lexer's current token is null if end of file is not reached and no whitespace token or other token matched
 *
 */
class StandardLexer implements Lexer
{
    /** @var array */
    private $tokenMatchersList;

    /** @var TextMatcher */
    private $whitespaceMatcher;

    /** @var TokenMatchStrategy */
    private $matchStrategy;

    /** @var TextReader */
    private $textReader;

    /** @var CharPosition position of the text reader cursror*/
    private $textPosition;

    /** @var StringToken */
    private $token;

    /** @var StringToken */
    private $peekToken;

    /** @var boolean status variable that indicates if a token has matched */
    private $hasTokenMatch = false;

    /** @var boolean */
    private $hasPeekToken = false;

    /**
     * @param array|TokenMatchReader[] $tokenMatchersList
     * @param TokenMatchStrategy $matchStrategy
     * @param TextReader $textReader
     * @param TextMatcher $whitespaceMatcher
     */
    public function __construct(
        array $tokenMatchersList,
        TextMatcher $whitespaceMatcher,
        TokenMatchStrategy $matchStrategy,
        TextReader $textReader
    ) {
        $this->tokenMatchersList = $tokenMatchersList;
        $this->whitespaceMatcher = $whitespaceMatcher;
        $this->matchStrategy = $matchStrategy;
        $this->textReader = $textReader;

        $this->textPosition = new CharPosition(0, 0, 0);
        $token = $this->readNextToken();
        if (!is_null($token)) {
            $this->token = $token;
            $this->hasTokenMatch = true;
        }
    }

    /**
     * @return EOFToken|StringToken
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
        if (! $this->hasTokenMatch || $this->token instanceof EOFToken) {
            return false;
        }

        if ($this->hasPeekToken) {
            $this->consumePeekToken();
            return true;
        }

        //read next token
        $token = $this->readNextToken();
        if (is_null($token)) {
            $this->token = null;
            $this->hasTokenMatch = false;
            return false;
        }

        $this->token = $token;
        return true;
    }

    /**
     * @return StringToken|null
     * @throws \Exception
     * @throws \RuntimeException
     */
    public function peekToken()
    {
        if ($this->hasPeekToken) {
            return $this->peekToken;
        }

        if ($this->token instanceof EOFToken) {
            throw new \Exception('Can not peek over EOF');
        }

        //read next token
        $token = $this->readNextToken();
        $this->peekToken = $token;

        //if $token is null what should happen? throw exception

        $this->hasPeekToken = true;
        return $token;
    }

    private function consumePeekToken()
    {
        $this->token = $this->peekToken;
        $this->peekToken = null;
        $this->hasPeekToken = false;
    }

    /**
     * @return StringToken|null
     */
    private function readNextToken()
    {
        //read next token
        $position = $this->textPosition;
        $token = $this->readToken($position);

        if (is_null($token)) { // check if token is null because end-of-file was reached
            $nextChar = $this->textReader->readCharacter();
            if (is_null($nextChar)) {
                return new EOFToken($this->textPosition);
            }
        }

        return $token;
    }

    /**
     * @param CharPosition $from
     *
     * @return StringToken|null
     */
    private function readToken(CharPosition $from)
    {
        /** @var CharPosition $position */
        $position = null;

        $whitespaceMatch = $this->matchWhitespace();
        if (is_null($whitespaceMatch)) {
            $position = $from;
        } else {
            $position = $this->calculateReaderPosition($whitespaceMatch, $from);
            $this->advanceReader($whitespaceMatch);
        }

        $tokenMatch = $this->matchToken();
        if (is_null($tokenMatch)) {
            return null;
        }

        $this->calculateReaderPosition($tokenMatch, $position);
        $this->advanceReader($tokenMatch);

        $token = $this->createToken($tokenMatch, $position);
        return $token;
    }

    /**
     * @param TokenMatch $tokenMatch
     * @param CharPosition $tokenPosition
     *
     * @return StringToken
     */
    private function createToken(TokenMatch $tokenMatch, CharPosition $tokenPosition)
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
        $match = $this->matchStrategy->match($this->textReader, $this->tokenMatchersList);
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
     * @param TokenMatch $lastMatch
     *
     * @return bool
     */
    private function advanceReader(TokenMatch $lastMatch)
    {
        //advance the reader
        $advanceBytes = $lastMatch->getByteLength();
        $this->textReader->skip($advanceBytes);
        return true;
    }

    /**
     * Calculates the reader position after the match and updates the internal property
     *
     * @param TokenMatch $textMatch
     * @param CharPosition $previous
     *
     * @return CharPosition
     */
    private function calculateReaderPosition(TokenMatch $textMatch, CharPosition $previous)
    {
        $offsetPosition = $this->calculatePositionOffset($textMatch);
        $nextPosition   = $previous->offsetRight($offsetPosition);

        $this->textPosition = $nextPosition;
        return $nextPosition;
    }

    /**
     * @param TokenMatch $textMatch
     *
     * @return CharPosition
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
        $offsetPosition = new CharPosition($offsetByte, $startColumn + $offsetColumn, $offsetLines);
        return $offsetPosition;
    }
}
