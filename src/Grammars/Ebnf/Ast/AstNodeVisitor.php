<?php namespace Helstern\Nomsky\Grammars\Ebnf\Ast;

interface AstNodeVisitor
{
    /**
     * @param SyntaxNode $node
     * @return boolean
     */
    public function visitSyntaxNode(SyntaxNode $node);

    /**
     * @param ProductionNode $node
     * @return boolean
     */
    public function visitProductionNode(ProductionNode $node);

    /**
     * @param AlternativeNode $node
     * @return boolean
     */
    public function visitAlternativeNode(AlternativeNode $node);

    /**'
     * @param SequenceNode $node
     * @return boolean
     */
    public function visitSequenceNode(SequenceNode $node);

    /**
     * @param RepeatedExpressionNode $node
     * @return boolean
     */
    public function visitOptionalExpressionListNode(RepeatedExpressionNode $node);

    /**
     * @param OptionalExpressionNode $node
     * @return boolean
     */
    public function visitOptionalExpressionNode(OptionalExpressionNode $node);

    /**
     * @param GroupedExpressionNode $node
     * @return boolean
     */
    public function visitGroupNode(GroupedExpressionNode $node);

    /**
     * @param IdentifierNode $node
     * @return boolean
     */
    public function visitIdentifierNode(IdentifierNode $node);

    /**
     * @param StringLiteralNode $node
     * @return boolean
     */
    public function visitLiteralNode(StringLiteralNode $node);
}
