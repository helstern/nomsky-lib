<?php namespace Helstern\Nomsky\Grammars\Ebnf\Ast;

use Helstern\Nomsky\Parser\Ast\AstNode;
use Helstern\Nomsky\Parser\TokenPosition;

class SpecialSequenceNode extends AbstractEbnfNode implements AstNode
{
    /** @var TokenPosition */
    protected $textPosition;

    public function __construct(TokenPosition $textPosition, $rawComment)
    {
        $this->textPosition = $textPosition;
        $this->string = $rawComment;
    }

    public function getTextPosition()
    {
        return $this->textPosition;
    }
}
