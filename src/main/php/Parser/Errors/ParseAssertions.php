<?php namespace Helstern\Nomsky\Parser\Errors;

use Helstern\Nomsky\Parser\ParseException\SyntacticException;
use Helstern\Nomsky\Parser\Token;
use Helstern\Nomsky\Tokens\TokenPredicates;
use Helstern\Nomsky\Tokens\StringToken;

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
     *
     * @return bool
     * @throws SyntacticException
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
     *
     * @param Token $actualToken
     *
     * @return bool
     * @throws SyntacticException
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
