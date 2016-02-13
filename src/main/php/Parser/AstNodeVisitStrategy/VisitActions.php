<?php namespace Helstern\Nomsky\Parser\AstNodeVisitStrategy;

use Helstern\Nomsky\Parser\Ast\AstNode;
use Helstern\Nomsky\Parser\Ast\VisitActionFactory;
use Helstern\Nomsky\Parser\AstNodeVisitor\ActualVisitAction;
use Helstern\Nomsky\Parser\AstNodeVisitor\PostVisitAction;
use Helstern\Nomsky\Parser\AstNodeVisitor\PreVisitAction;

class VisitActions implements VisitActionFactory
{
    /** @var AstNodeVisitorProvider  */
    protected $visitorProvider;

    /**
     * @param AstNodeVisitorProvider $visitorProvider
     */
    public function __construct(AstNodeVisitorProvider $visitorProvider)
    {
        $this->visitorProvider = $visitorProvider;
    }

    /**
     * @param AstNode $astNode
     * @return NodeVisitActions
     */
    public function getNodeVisitActions(AstNode $astNode)
    {
        $visitor = $this->visitorProvider->getVisitor($astNode);
        $nodeVisitActions = new NodeVisitActions($astNode, $visitor);

        return $nodeVisitActions;
    }

    /**
     * @param AstNode $astNode
     * @return PreVisitAction
     */
    public function createPreVisit(AstNode $astNode)
    {
        $visitor = $this->visitorProvider->getVisitor($astNode);
        $action = new PreVisitAction($astNode, $visitor);

        return $action;
    }

    /**
     * @param AstNode $astNode
     * @return PreVisitAction
     */
    public function createPostVisit(AstNode $astNode)
    {
        $visitor = $this->visitorProvider->getVisitor($astNode);
        $action = new PostVisitAction($astNode, $visitor);

        return $action;
    }

    /**
     * @param AstNode $astNode
     * @return PreVisitAction
     */
    public function createActualVisit(AstNode $astNode)
    {
        $visitor = $this->visitorProvider->getVisitor($astNode);
        $action = new ActualVisitAction($astNode, $visitor);

        return $action;
    }
}

