<?php namespace Helstern\Nomsky\ParseAssertion;

use Helstern\Nomsky\Lexer\NomskyTokenTypesEnum;
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
     * @param $msg
     * @param Token $token
     * @param int $type
     * @throws SyntacticException
     * @return bool
     */
    public function assertSameType($msg, Token $token, $type)
    {
        if ($this->tokenPredicates->hasSameType($token, $type)) {
            return true;
        }

        throw new SyntacticException($token, $msg, SyntacticExceptionCodes::CODE_BASE);
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
        if ($token->getType() !== NomskyTokenTypesEnum::ENUM_EOF) {
            return true;
        }

        throw new SyntacticException($token, $msg, SyntacticExceptionCodes::CODE_BASE);
    }
}
