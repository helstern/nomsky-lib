<?php namespace Helstern\Nomsky\Parser\EbnfAst;

use Helstern\Nomsky\TextMatch\CharacterPosition;

class ProductionNode extends AbstractCompositeAstNode implements AstNode
{
    /** @var CharacterPosition */
    protected $textPosition;

    /** @var IdentifierNode */
    protected $identifierNode;

    /** @var AstNode */
    protected $expressionNode;

    public function __construct(CharacterPosition $textPosition, IdentifierNode $identifier, AstNode $expressionNode)
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
