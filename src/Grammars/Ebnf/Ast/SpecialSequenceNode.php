<?php namespace Helstern\Nomsky\Grammars\Ebnf\Ast;

use Helstern\Nomsky\Parser\Ast\AstNode;
use Helstern\Nomsky\Text\TextPosition;

class SpecialSequenceNode implements AstNode
{
    /** @var TextPosition */
    protected $textPosition;

    public function __construct(TextPosition $textPosition, $rawComment)
    {
        $this->textPosition = $textPosition;
        $this->string = $rawComment;
    }

    public function getTextPosition()
    {
        return $this->textPosition;
    }
}
