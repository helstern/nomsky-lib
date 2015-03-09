<?php namespace Helstern\Nomsky\Parser\Errors;

use Helstern\Nomsky\Parser\ParseException\SyntacticException;
use Helstern\Nomsky\Tokens\TokenPredicates;
use Helstern\Nomsky\Tokens\Token;

use Helstern\Nomsky\Parser\ParseException\SyntacticExceptionCodes;

class ParseAssertions
{
    /** @var TokenPredicates */
    protected $tokenPredicates;

    public function __construct(TokenPredicates $tokenPredicates)
    {
        $this->tokenPredicates = $tokenPredicates;
    }

    /**
     * @return TokenPredicates
     */
    public function getTokenPredicates() {
        return $this->tokenPredicates;
    }

    /**
     * @param Token $actualToken
     * @param $expectedType
     * @return bool
     * @throws \Helstern\Nomsky\Parser\ParseException\SyntacticException
     */
    public function assertValidTokenType(Token $actualToken, $expectedType)
    {
        if ($this->tokenPredicates->hasSameType($actualToken, $expectedType)) {
            return true;
        }

        $exceptionMsg = (new ErrorMessagesBuilder)->invalidTokenAtPosition($actualToken, $expectedType);
        throw new SyntacticException($actualToken, $exceptionMsg, SyntacticExceptionCodes::CODE_BASE);
    }

    /**
     * @param \Helstern\Nomsky\Tokens\Token $actualToken
     * @throws \Helstern\Nomsky\Parser\ParseException\SyntacticException
     * @return bool
     */
    public function assertNotEOF(Token $actualToken)
    {
        if ($this->tokenPredicates->hasSameType($actualToken, 0)) {
            $exceptionMsg = (new ErrorMessagesBuilder)->unexpectedEOF($actualToken);
            throw new SyntacticException($actualToken, $exceptionMsg, SyntacticExceptionCodes::CODE_BASE);
        }

        return true;
    }
}
