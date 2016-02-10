<?php namespace Helstern\Nomsky\Parser\Errors;

use Helstern\Nomsky\Tokens\StringToken;

class ErrorMessagesBuilder
{
    /**
     * @param StringToken $invalidToken
     * @param $expectedType
     *
*@return string
     */
    public function invalidTokenAtPosition(StringToken $invalidToken, $expectedType)
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
     * @param StringToken $eofToken
     *
*@return string
     */
    public function unexpectedEOF(StringToken $eofToken)
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
