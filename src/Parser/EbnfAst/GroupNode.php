<?php namespace Helstern\Nomsky\Parser\EbnfAst;

use Helstern\Nomsky\TextMatch\CharacterPosition;

class GroupNode extends AbstractCompositeAstNode implements AstNode
{
    /** @var CharacterPosition */
    protected $textPosition;

    /** @var AstNode */
    protected $childNode;

    public function __construct(CharacterPosition $textPosition, AstNode $childNode)
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
}
