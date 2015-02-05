<?php namespace Helstern\Nomsky\Parser\EbnfAst;

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
     * @param OptionalExpressionListNode $node
     * @return boolean
     */
    public function visitOptionalExpressionListNode(OptionalExpressionListNode $node);

    /**
     * @param OptionalExpressionNode $node
     * @return boolean
     */
    public function visitOptionalExpressionNode(OptionalExpressionNode $node);

    /**
     * @param GroupNode $node
     * @return boolean
     */
    public function visitGroupNode(GroupNode $node);

    /**
     * @param IdentifierNode $node
     * @return boolean
     */
    public function visitIdentifierNode(IdentifierNode $node);

    /**
     * @param LiteralNode $node
     * @return boolean
     */
    public function visitLiteralNode(LiteralNode $node);
}
