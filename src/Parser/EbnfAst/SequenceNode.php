<?php namespace Helstern\Nomsky\Parser\EbnfAst;

use Helstern\Nomsky\TextMatch\CharacterPosition;

class SequenceNode extends AbstractCompositeAstNode implements AstNode
{
    /** @var CharacterPosition */
    protected $textPosition;

    /** @var AstNode */
    protected $firstChild;

    /** @var array|AstNode[] */
    protected $otherChildren;

    /**
     * @param CharacterPosition $textPosition
     * @param AstNode $firstChild
     * @param array $otherChildren
     */
    public function __construct(CharacterPosition $textPosition, AstNode $firstChild, array $otherChildren)
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
