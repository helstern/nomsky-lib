<?php namespace Helstern\Nomsky\Parser\ParseException;

use Exception;
use Helstern\Nomsky\Text\TextPosition;
use Helstern\Nomsky\Tokens\StringToken;

class SyntacticException extends \Exception
{
    /** @var StringToken */
    protected $illegalToken;

    public function __construct(StringToken $illegalToken, $message, $code = 0)
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
