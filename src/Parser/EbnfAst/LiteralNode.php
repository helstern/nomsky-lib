<?php namespace Helstern\Nomsky\Parser\EbnfAst;

use Helstern\Nomsky\TextMatch\CharacterPosition;

class LiteralNode implements AstNode
{
    /** @var CharacterPosition */
    protected $textPosition;

    public function __construct(CharacterPosition $textPosition, $string)
    {
        $this->textPosition = $textPosition;
        $this->string = $string;
    }

    public function getTextPosition()
    {
        return $this->textPosition;
    }
}
