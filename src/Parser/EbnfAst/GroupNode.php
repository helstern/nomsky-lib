<?php namespace Helstern\Nomsky\Parser\EbnfAst;

use Helstern\Nomsky\Text\TextPosition;

class GroupNode extends AbstractCompositeAstNode implements AstNode
{
    /** @var TextPosition */
    protected $textPosition;

    /** @var AstNode */
    protected $childNode;

    public function __construct(TextPosition $textPosition, AstNode $childNode)
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
