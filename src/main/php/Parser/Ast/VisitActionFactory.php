<?php namespace Helstern\Nomsky\Parser\Ast;

interface VisitActionFactory
{
    /**
     * @param AstNode $astNode
     * @param AstNodeVisitor $visitor
     *
     * @return VisitAction
     */
    public function createPreVisit(AstNode $astNode, AstNodeVisitor $visitor);

    /**
     * @param AstNode $astNode
     * @param AstNodeVisitor $visitor
     *
     * @return VisitAction
     */
    public function createPostVisit(AstNode $astNode, AstNodeVisitor $visitor);

    /**
     * @param AstNode $astNode
     * @param AstNodeVisitor $visitor
     *
     * @return VisitAction
     */
    public function createActualVisit(AstNode $astNode, AstNodeVisitor $visitor);
}
