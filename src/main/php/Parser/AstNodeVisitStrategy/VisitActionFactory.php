<?php namespace Helstern\Nomsky\Parser\AstNodeVisitStrategy;

use Helstern\Nomsky\Parser\Ast\AstNode;
use Helstern\Nomsky\Parser\Ast\AstNodeVisitor;
use Helstern\Nomsky\Parser\AstNodeVisitor\VisitAction;

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
