<?php namespace Helstern\Nomsky\Grammars\Ebnf\Ast;

use Helstern\Nomsky\Parser\Ast\CompositeAstNode;
use Helstern\Nomsky\Parser\Ast\AstNode;
use Helstern\Nomsky\Text\TextPosition;

class RuleNode extends AbstractEbnfNode implements AstNode, CompositeAstNode
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

    /**
     * @return int
     */
    public function countChildren()
    {
        return 2;
    }
}
