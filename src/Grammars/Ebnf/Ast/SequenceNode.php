<?php namespace Helstern\Nomsky\Grammars\Ebnf\Ast;

use Helstern\Nomsky\Parser\Ast\CompositeAstNode;
use Helstern\Nomsky\Parser\Ast\AstNode;
use Helstern\Nomsky\Text\TextPosition;

class SequenceNode extends AbstractEbnfNode implements AstNode, CompositeAstNode
{
    /** @var TextPosition */
    protected $textPosition;

    /** @var AstNode */
    protected $firstChild;

    /** @var array|AstNode[] */
    protected $otherChildren;

    /**
     * @param TextPosition $textPosition
     * @param AstNode $firstChild
     * @param array $otherChildren
     */
    public function __construct(TextPosition $textPosition, AstNode $firstChild, array $otherChildren)
    {
        $this->textPosition = $textPosition;
        $this->firstChild = $firstChild;
        $this->otherChildren = $otherChildren;
    }

    public function getChildren()
    {
        $children = array_merge(array($this->firstChild), $this->otherChildren);
        return $children;
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
       return 1 + count($this->otherChildren);
    }
}
