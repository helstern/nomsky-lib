<?php namespace Helstern\Nomsky\Grammars\Ebnf\Ast;

use Helstern\Nomsky\Parser\Ast\CompositeAstNode;
use Helstern\Nomsky\Parser\Ast\AstNode;
use Helstern\Nomsky\Parser\TokenPosition;

class RepeatedExpressionNode extends AbstractEbnfNode implements AstNode, CompositeAstNode
{
    /** @var TokenPosition */
    protected $textPosition;

    /** @var AstNode */
    protected $childNode;

    public function __construct(TokenPosition $textPosition, AstNode $expression)
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

    /**
     * @return int
     */
    public function countChildren()
    {
        return 1;
    }
}
