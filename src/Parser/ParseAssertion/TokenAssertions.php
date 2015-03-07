<?php namespace Helstern\Nomsky\Parser\ParseAssertion;

use Helstern\Nomsky\Parser\ErrorMessages\MessagesBuilder;
use Helstern\Nomsky\Parser\ParseException\SyntacticException;
use Helstern\Nomsky\Tokens\TokenPredicates;
use Helstern\Nomsky\Tokens\Token;

use Helstern\Nomsky\Parser\ParseException\SyntacticExceptionCodes;

class TokenAssertions
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
    public function getPredicates() {
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
        $exceptionMsg = (new MessagesBuilder)->invalidTokenAtPosition($actualToken, $expectedType);
        throw new SyntacticException($actualToken, $exceptionMsg, SyntacticExceptionCodes::CODE_BASE);
    }

    /**
     * @param $msg
     * @param Token $token
     * @param int $type
     * @param string $value
     * @throws SyntacticException
     * @return bool
     */
    public function assertSameTypeAndValue($msg, Token $token, $type, $value)
    {
        if ($this->tokenPredicates->hasSameTypeAndValue($token, $type, $value)) {
            return true;
        }

        throw new SyntacticException($token, $msg, SyntacticExceptionCodes::CODE_BASE);
    }

    /**
     * @param $msg
     * @param Token $token
     * @param int $type
     * @param array $values
     * @throws SyntacticException
     * @return bool
     */
    public function assertSameTypeAndAnyValue($msg, Token $token, $type, array $values)
    {
        if ($this->tokenPredicates->hasSameTypeAndAnyValue($token, $type, $values)) {
            return true;
        }

        throw new SyntacticException($token, $msg, SyntacticExceptionCodes::CODE_BASE);
    }

    /**
     * @param $msg
     * @param Token $token
     * @return bool
     * @throws SyntacticException
     */
    public function assertNotEOF($msg, Token $token)
    {
        if ($token->getType() === 0) {
            throw new SyntacticException($token, $msg, SyntacticExceptionCodes::CODE_BASE);
        }

        return true;
    }
}
