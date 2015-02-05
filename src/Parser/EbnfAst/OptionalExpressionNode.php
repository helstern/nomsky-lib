<?php namespace Helstern\Nomsky\Parser\EbnfAst;

use Helstern\Nomsky\TextMatch\CharacterPosition;

class OptionalExpressionNode extends AbstractCompositeAstNode implements AstNode
{
    /** @var CharacterPosition */
    protected $textPosition;

    /** @var AstNode */
    protected $childNode;

    public function __construct(CharacterPosition $textPosition, AstNode $expression)
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
