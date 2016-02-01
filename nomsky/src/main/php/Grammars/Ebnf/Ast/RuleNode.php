<?php namespace Helstern\Nomsky\Grammars\Ebnf\Ast;

use Helstern\Nomsky\Parser\Ast\CompositeAstNode;
use Helstern\Nomsky\Parser\Ast\AstNode;
use Helstern\Nomsky\Parser\TokenPosition;

class RuleNode extends AbstractEbnfNode implements AstNode, CompositeAstNode
{
    /** @var TokenPosition */
    protected $textPosition;

    /** @var IdentifierNode */
    protected $identifierNode;

    /** @var AstNode */
    protected $expressionNode;

    public function __construct(TokenPosition $textPosition, IdentifierNode $identifier, AstNode $expressionNode)
    {
        $this->textPosition = $textPosition;
        $this->identifierNode = $identifier;
        $this->expressionNode = $expressionNode;
    }

    /**
     * @return IdentifierNode
     */
    public function getIdentifierNode()
    {
        return $this->identifierNode;
    }

    /**
     * @return AstNode
     */
    public function getExpressionNode()
    {
        return $this->expressionNode;
    }

    public function getChildren()
    {
        return array($this->identifierNode, $this->expressionNode);
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
        return 2;
    }
}
