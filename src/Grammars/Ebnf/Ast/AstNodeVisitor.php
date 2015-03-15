<?php namespace Helstern\Nomsky\Grammars\Ebnf\Ast;

interface AstNodeVisitor
{
    /**
     * @param SyntaxNode $node
     * @return boolean
     */
    public function visitSyntaxNode(SyntaxNode $node);

    /**
     * @param RuleNode $node
     * @return boolean
     */
    public function visitRuleNode(RuleNode $node);


    /**
     * @param AlternativeNode $node
     * @return boolean
     */
    public function preVisitAlternativeNode(AlternativeNode $node);

    /**
     * @param AlternativeNode $node
     * @return boolean
     */
    public function visitAlternativeNode(AlternativeNode $node);

    /**
     * @param AlternativeNode $node
     * @return boolean
     */
    public function postVisitAlternativeNode(AlternativeNode $node);


    /**'
     * @param SequenceNode $node
     * @return boolean
     */
    public function visitSequenceNode(SequenceNode $node);

    /**
     * @param RepeatedExpressionNode $node
     * @return boolean
     */
    public function visitRepeatedExpressionNode(RepeatedExpressionNode $node);

    /**
     * @param OptionalExpressionNode $node
     * @return boolean
     */
    public function visitOptionalExpressionNode(OptionalExpressionNode $node);

    /**
     * @param GroupedExpressionNode $node
     * @return boolean
     */
    public function visitGroupedExpressionNode(GroupedExpressionNode $node);

    /**
     * @param IdentifierNode $node
     * @return boolean
     */
    public function visitIdentifierNode(IdentifierNode $node);

    /**
     * @param StringLiteralNode $node
     * @return boolean
     */
    public function visitStringLiteralNode(StringLiteralNode $node);

    /**
     * @param CommentNode $node
     * @return boolean
     */
    public function visitCommentNode(CommentNode $node);

    /**
     * @param SpecialSequenceNode $node
     * @return boolean
     */
    public function visitSpecialSequenceNode(SpecialSequenceNode $node);
}
