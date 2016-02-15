<?php namespace Helstern\Nomsky\Parser\AstNodeVisitStrategy;

use Helstern\Nomsky\Parser\Ast\AstNode;
use Helstern\Nomsky\Parser\Ast\AstNodeVisitor;
use Helstern\Nomsky\Parser\Ast\VisitActionFactory;
use Helstern\Nomsky\Parser\AstNodeVisitor\ActualVisitAction;
use Helstern\Nomsky\Parser\AstNodeVisitor\PostVisitAction;
use Helstern\Nomsky\Parser\AstNodeVisitor\PreVisitAction;

class VisitActions implements VisitActionFactory
{
    /**
     * @param AstNode $astNode
     * @param AstNodeVisitor $visitor
     *
     * @return PreVisitAction
     */
    public function createPreVisit(AstNode $astNode, AstNodeVisitor $visitor)
    {
        $action = new PreVisitAction($astNode, $visitor);
        return $action;
    }

    /**
     * @param AstNode $astNode
     * @param AstNodeVisitor $visitor
     *
     * @return PreVisitAction
     */
    public function createPostVisit(AstNode $astNode, AstNodeVisitor $visitor)
    {
        $action = new PostVisitAction($astNode, $visitor);
        return $action;
    }

    /**
     * @param AstNode $astNode
     * @param AstNodeVisitor $visitor
     *
     * @return PreVisitAction
     */
    public function createActualVisit(AstNode $astNode, AstNodeVisitor $visitor)
    {
        $action = new ActualVisitAction($astNode, $visitor);
        return $action;
    }
}

