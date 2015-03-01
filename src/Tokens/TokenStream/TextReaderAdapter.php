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

        $previousPosition = new TextPosition(0, 0, 0);
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
        if (is_null($tokenMatch)) {
            return $this->createEOFToken();
        }

        $this->textReader->skip($tokenMatch->getByteLength());

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

        $offsetLines = (int) preg_match("#\r\n|\n|\r#m", $offsetTextMatch, $newLineMatches, PREG_OFFSET_CAPTURE);
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

        $previousPosition = $this->token->getPosition();
        $nextToken = $this->readNextToken($previousPosition);
        if ($this->isEOFToken($nextToken)) {
            $this->eofToken = $nextToken;
            $this->token = null;

            return false;
        }

        $this->token = $nextToken;
        return true;
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
