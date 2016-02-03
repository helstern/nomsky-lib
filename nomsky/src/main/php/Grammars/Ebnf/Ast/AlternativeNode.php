<?php namespace Helstern\Nomsky\Grammars\Ebnf\Ast;

use Helstern\Nomsky\Parser\Ast\CompositeAstNode;
use Helstern\Nomsky\Parser\Ast\AstNode;
use Helstern\Nomsky\Parser\CharPosition;

class AlternativeNode extends AbstractEbnfNode implements AstNode, CompositeAstNode
{
    /** @var CharPosition */
    protected $textPosition;

    /** @var AstNode */
    protected $firstChild;

    /** @var array|AstNode[] */
    protected $otherChildren;

    /**
     * @param CharPosition $textPosition
     * @param AstNode $firstChild
     * @param array $otherChildren
     */
    public function __construct(CharPosition $textPosition, AstNode $firstChild, array $otherChildren)
    {
        $this->textPosition = $textPosition;
        $this->firstChild = $firstChild;
        $this->otherChildren = $otherChildren;
    }

    public function getChildren()
    {
        $children = array_merge(array($this->firstChild),$this->otherChildren);
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
