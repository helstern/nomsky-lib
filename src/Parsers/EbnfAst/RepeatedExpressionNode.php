<?php namespace Helstern\Nomsky\Parsers\EbnfAst;

use Helstern\Nomsky\Parser\AstNode;
use Helstern\Nomsky\Text\TextPosition;

class RepeatedExpressionNode extends AbstractCompositeAstNode implements AstNode
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
