<?php namespace Helstern\Nomsky\Parser\Errors;

use Helstern\Nomsky\Tokens\Token;

class ErrorMessagesBuilder
{
    /**
     * @param Token $invalidToken
     * @param $expectedType
     * @return string
     */
    public function invalidTokenAtPosition(Token $invalidToken, $expectedType)
    {
        $msgTemplate = 'invalid token type %s at line %s, column %s. expected token type %s';
        $msg = sprintf(
            $msgTemplate,
            $invalidToken->getType(),
            $invalidToken->getPosition()->getLine(),
            $invalidToken->getPosition()->getColumn(),
            $expectedType
        );

        return $msg;
    }

    /**
     * @param Token $eofToken
     * @return string
     */
    public function unexpectedEOF(Token $eofToken)
    {
        $msgTemplate = 'unexpected end-of-file at at line %s, column %s';
        $msg = sprintf(
            $msgTemplate
            , $eofToken->getPosition()->getLine()
            , $eofToken->getPosition()->getColumn()
        );

        return $msg;
    }
}
