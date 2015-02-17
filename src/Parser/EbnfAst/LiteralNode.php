<?php namespace Helstern\Nomsky\Parser\EbnfAst;

use Helstern\Nomsky\Text\TextPosition;

class LiteralNode implements AstNode
{
    /** @var TextPosition */
    protected $textPosition;

    public function __construct(TextPosition $textPosition, $string)
    {
        $this->textPosition = $textPosition;
        $this->string = $string;
    }

    public function getTextPosition()
    {
        return $this->textPosition;
    }
}
