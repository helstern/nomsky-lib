<?php namespace Helstern\Nomsky\Grammars\Ebnf\Graphviz\DotWriterVisitors;

use Helstern\Nomsky\Grammars\Ebnf\Ast\RepeatedExpressionNode;
use Helstern\Nomsky\Grammars\Ebnf\Graphviz\VisitorCollaborators;
use Helstern\Nomsky\Parser\Ast\AstNodeVisitor;
use Helstern\Nomsky\Parser\AstNodeVisitor\AbstractDispatchingVisitor;
use Helstern\Nomsky\Parser\AstNodeVisitor\VisitDispatcher;

class RepeatedExpressionNodeVisitor extends AbstractDispatchingVisitor implements AstNodeVisitor
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
     * @param RepeatedExpressionNode $astNode
     * @return string
     */
    protected function buildDOTNodeId(RepeatedExpressionNode $astNode)
    {
        $nodeCounter = $this->collaborators->nodeCounter();
        $idNumber = $nodeCounter->getNodeCount();

        return '"' . 'repeated_expression' . '[' .$idNumber . ']' . '"';
    }

    public function preVisitRepeatedExpressionNode(RepeatedExpressionNode $astNode)
    {
        $nodeCounter = $this->collaborators->nodeCounter();
        $nodeCounter->increment($astNode);
    }

    public function visitRepeatedExpressionNode(RepeatedExpressionNode $astNode)
    {
        $dotWriter = $this->collaborators->dotWriter();

        $nodeId    = $this->buildDOTNodeId($astNode);
        $parents = $this->collaborators->parentNodeIds();
        $parentId = $parents->top();

        $parents->push($nodeId);

        $dotWriter->writeEdgeStatement($parentId, $nodeId);
        $dotWriter->writeStatementTerminator();
    }

    public function postVisitRepeatedExpressionNode(RepeatedExpressionNode $astNode)
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
