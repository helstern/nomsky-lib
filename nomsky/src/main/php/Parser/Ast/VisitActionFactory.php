<?php namespace Helstern\Nomsky\Parser\Ast;

interface VisitActionFactory
{
    /**
     * @param AstNode $astNode
     * @return VisitAction
     */
    public function createPreVisit(AstNode $astNode);

    /**
     * @param AstNode $astNode
     * @return VisitAction
     */
    public function createPostVisit(AstNode $astNode);

    /**
     * @param AstNode $astNode
     * @return VisitAction
     */
    public function createActualVisit(AstNode $astNode);
}
