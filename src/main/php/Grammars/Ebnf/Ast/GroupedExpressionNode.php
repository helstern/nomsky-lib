<?php namespace Helstern\Nomsky\Grammars\Ebnf\Ast;

use Helstern\Nomsky\Parser\Ast\CompositeAstNode;
use Helstern\Nomsky\Parser\Ast\AstNode;
use Helstern\Nomsky\Parser\CharPosition;

class GroupedExpressionNode extends AbstractEbnfNode implements AstNode, CompositeAstNode
{
    /** @var CharPosition */
    protected $textPosition;

    /** @var AstNode */
    protected $childNode;

    public function __construct(CharPosition $textPosition, AstNode $childNode)
    {
        $this->textPosition = $textPosition;
        $this->childNode = $childNode;
    }

    public function getTextPosition()
    {
        return $this->textPosition;
    }

    public function getChildren()
    {
        return array($this->childNode);
    }

    /**
     * @return int
     */
    public function countChildren()
    {
        return 1;
    }
}
