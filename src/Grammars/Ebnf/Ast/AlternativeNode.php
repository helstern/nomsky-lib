<?php namespace Helstern\Nomsky\Grammars\Ebnf\Ast;

use Helstern\Nomsky\Parser\Ast\AbstractCompositeAstNode;
use Helstern\Nomsky\Parser\Ast\AstNode;
use Helstern\Nomsky\Text\TextPosition;

class AlternativeNode extends AbstractCompositeAstNode implements AstNode
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
        return array($this->firstChild) + $this->otherChildren;
    }

    public function getTextPosition()
    {
        return $this->textPosition;
    }
}
