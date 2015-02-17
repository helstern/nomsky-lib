<?php namespace Helstern\Nomsky\Parser\EbnfAst;

use Helstern\Nomsky\Text\TextPosition;

class ProductionNode extends AbstractCompositeAstNode implements AstNode
{
    /** @var TextPosition */
    protected $textPosition;

    /** @var IdentifierNode */
    protected $identifierNode;

    /** @var AstNode */
    protected $expressionNode;

    public function __construct(TextPosition $textPosition, IdentifierNode $identifier, AstNode $expressionNode)
    {
        $this->textPosition = $textPosition;
        $this->identifierNode = $identifier;
        $this->expressionNode = $expressionNode;
    }

    public function getChildren()
    {
        return array($this->identifierNode, $this->expressionNode);
    }

    public function getTextPosition()
    {
        return $this->textPosition;
    }
}
