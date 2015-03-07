<?php namespace Helstern\Nomsky\Parser\ErrorMessages;

use Helstern\Nomsky\Tokens\Token;

class MessagesBuilder
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
}
