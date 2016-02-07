<?php namespace Helstern\Nomsky\Grammars\Ebnf\Ast;

use Helstern\Nomsky\Parser\Ast\AstNode;
use Helstern\Nomsky\Parser\CharPosition;

class CommentNode extends AbstractEbnfNode implements AstNode
{
    /** @var CharPosition */
    protected $textPosition;

    public function __construct(CharPosition $textPosition, $rawComment)
    {
        $this->textPosition = $textPosition;
        $this->string = $rawComment;
    }

    public function getTextPosition()
    {
        return $this->textPosition;
    }
}
