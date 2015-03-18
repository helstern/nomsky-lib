<?php namespace Helstern\Nomsky\Grammars\Ebnf\Graphviz\DotWriterVisitors;

use Helstern\Nomsky\Grammars\Ebnf\Ast\GroupedExpressionNode;
use Helstern\Nomsky\Grammars\Ebnf\Graphviz\VisitorCollaborators;
use Helstern\Nomsky\Parser\Ast\AstNodeVisitor;
use Helstern\Nomsky\Parser\AstNodeVisitor\AbstractDispatchingVisitor;
use Helstern\Nomsky\Parser\AstNodeVisitor\VisitDispatcher;

class GroupedExpressionNodeVisitor extends AbstractDispatchingVisitor implements AstNodeVisitor
{
    /** @var VisitorCollaborators */
    protected $collaborators;

    /** @var VisitDispatcher  */
    protected $visitDispatcher;

    /**
     * @param VisitorCollaborators $collaborators
     * @param VisitDispatcher $visitDispatcher
     */
    public function __construct(VisitorCollaborators $collaborators, VisitDispatcher $visitDispatcher)
    {
        $this->collaborators = $collaborators;
        $this->visitDispatcher = $visitDispatcher;
    }

    /**
     * @param GroupedExpressionNode $astNode
     * @return string
     */
    protected function buildDOTNodeId(GroupedExpressionNode $astNode)
    {
        $nodeCounter = $this->collaborators->nodeCounter();
        $idNumber = $nodeCounter->getNodeCount();

        return '"' . 'grouped_expression' . '[' .$idNumber . ']' . '"';
    }

    public function preVisitGroupedExpressionNode(GroupedExpressionNode $astNode)
    {
        $nodeCounter = $this->collaborators->nodeCounter();
        $nodeCounter->increment($astNode);
    }

    public function visitGroupedExpressionNode(GroupedExpressionNode $astNode)
    {
        $dotWriter = $this->collaborators->dotWriter();

        $nodeId    = $this->buildDOTNodeId($astNode);
        $parents = $this->collaborators->parentNodeIds();
        $parentId = $parents->top();

        $parents->push($nodeId);

        $dotWriter->writeEdgeStatement($parentId, $nodeId);
        $dotWriter->writeStatementTerminator();
    }

    public function postVisitGroupedExpressionNode(GroupedExpressionNode $astNode)
    {
        $parents = $this->collaborators->parentNodeIds();
        $parents->pop();
    }

    /**
     * @return VisitDispatcher
     */
    protected function getVisitDispatcher()
    {
        $visitDispatcher = $this->visitDispatcher;
        return $visitDispatcher;
    }
}
