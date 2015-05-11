<?php namespace Helstern\Nomsky\Parser\ParseException;

use Exception;
use Helstern\Nomsky\Text\TextPosition;
use Helstern\Nomsky\Tokens\Token;

class SyntacticException extends \Exception
{
    /** @var Token */
    protected $illegalToken;

    public function __construct(Token $illegalToken, $message, $code = 0)
    {
        parent::__construct($message, $code);
        $this->illegalToken = $illegalToken;
    }

    /**
     * @return TextPosition
     */
    public function getTextPosition()
    {
        $textPosition = $this->illegalToken->getPosition();
        return $textPosition;
    }
}
