<?php namespace Helstern\Nomsky\Exception;

use Helstern\Nomsky\Text\TextPosition;

class SyntacticExceptionFactory
{
    /**
     * @param TextPosition $position
     * @param string $msg
     * @return SyntacticException
     */
    public function defaultException(TextPosition $position, $msg)
    {
        return new SyntacticException($msg, SyntacticExceptionCodes::CODE_BASE);
    }
}

