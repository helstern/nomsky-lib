<?php namespace Helstern\Nomsky\Parser\AstNodeWalkStrategy;

use Guzzle\Service\Command\LocationVisitor\Request\PostFieldVisitor;
use Helstern\Nomsky\Parser\Ast\AstNode;
use Helstern\Nomsky\Parser\Ast\AstNodeVisitor;
use Helstern\Nomsky\Parser\AstNodeVisitor\ActualVisitAction;
use Helstern\Nomsky\Parser\AstNodeVisitor\PreVisitAction;

class NodeVisitActions
{
    /** @var AstNode */
    protected $node;

    /** @var AstNodeVisitor */
    protected $visitor;

    /**
     * @param AstNode $node
     * @param AstNodeVisitor $visitor
     */
    public function __construct(AstNode $node, AstNodeVisitor $visitor)
    {
        $this->node = $node;
        $this->visitor = $visitor;
    }

    /**
     * @return PreVisitAction
     */
    public function preVisit()
    {
        $visit = new PreVisitAction($this->node, $this->visitor);
        return $visit;
    }

    /**
     * @return ActualVisitAction
     */
    public function actualVisit()
    {
        $visit = new ActualVisitAction($this->node, $this->visitor);
        return $visit;
    }

    /**
     * @return PostFieldVisitor
     */
    public function postVisit()
    {
        $visit = new PostFieldVisitor($this->node, $this->visitor);
        return $visit;
    }
}
