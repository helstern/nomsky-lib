<?php namespace Helstern\Nomsky\Grammars\Ebnf\Ast;

use Helstern\Nomsky\Parser\AstNode;
use Helstern\Nomsky\Text\TextPosition;

class CommentNode implements AstNode
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
