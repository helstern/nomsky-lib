<?php namespace Helstern\Nomsky\Parser\ParseException;

use Exception;
use Helstern\Nomsky\Parser\CharPosition;
use Helstern\Nomsky\Parser\Token;

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
     * @return CharPosition
     */
    public function getTextPosition()
    {
        $textPosition = $this->illegalToken->getPosition();
        return $textPosition;
    }
}
