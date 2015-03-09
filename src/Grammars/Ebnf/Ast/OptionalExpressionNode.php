<?php namespace Helstern\Nomsky\Grammars\Ebnf\Ast;

use Helstern\Nomsky\Parser\Ast\AbstractCompositeAstNode;
use Helstern\Nomsky\Parser\Ast\AstNode;
use Helstern\Nomsky\Text\TextPosition;

class OptionalExpressionNode extends AbstractCompositeAstNode implements AstNode
{
    /** @var TextPosition */
    protected $textPosition;

    /** @var AstNode */
    protected $childNode;

    public function __construct(TextPosition $textPosition, AstNode $expression)
    {
        $this->textPosition = $textPosition;
        $this->childNode = $expression;
    }

    public function getChildren()
    {
        return array($this->childNode);
    }

    public function getTextPosition()
    {
        return $this->textPosition;
    }
}
