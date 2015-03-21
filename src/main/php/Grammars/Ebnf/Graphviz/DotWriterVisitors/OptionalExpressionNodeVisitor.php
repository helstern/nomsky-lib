<?php namespace Helstern\Nomsky\Grammars\Ebnf\Graphviz\DotWriterVisitors;

use Helstern\Nomsky\Grammars\Ebnf\Ast\OptionalExpressionNode;
use Helstern\Nomsky\Grammars\Ebnf\Graphviz\VisitorCollaborators;
use Helstern\Nomsky\Parser\Ast\AstNodeVisitor;
use Helstern\Nomsky\Parser\AstNodeVisitor\AbstractDispatchingVisitor;
use Helstern\Nomsky\Parser\AstNodeVisitor\VisitDispatcher;

class OptionalExpressionNodeVisitor extends AbstractDispatchingVisitor implements AstNodeVisitor
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
     * @param OptionalExpressionNode $astNode
     * @return string
     */
    protected function buildDOTIdentifier(OptionalExpressionNode $astNode)
    {
        $nodeCounter = $this->collaborators->nodeCounter();
        $idNumber = $nodeCounter->getNodeCount();

        return '"' . 'optional_expression' . '[' .$idNumber . ']' . '"';
    }

    /**
     * @param OptionalExpressionNode $astNode
     * @return bool
     */
    public function preVisitOptionalExpressionNode(OptionalExpressionNode $astNode)
    {
        $nodeCounter = $this->collaborators->nodeCounter();
        $nodeCounter->increment($astNode);

        return true;
    }

    /**
     * @param OptionalExpressionNode $astNode
     * @return bool
     */
    public function visitOptionalExpressionNode(OptionalExpressionNode $astNode)
    {
        $dotWriter = $this->collaborators->dotWriter();
        $formatter = $this->collaborators->formatter();

        $parents = $this->collaborators->parentNodeIds();
        $increment = $parents->count();
        $formatter->indent($increment, $dotWriter);

        $nodeId    = $this->buildDOTIdentifier($astNode);
        $parents = $this->collaborators->parentNodeIds();
        $parentId = $parents->top();

        $dotWriter->writeEdgeStatement($parentId, $nodeId);
        $formatter->whitespace(1, $dotWriter); //formatting options
        $dotWriter->writeStatementTerminator();

        $parents->push($nodeId);

        return true;
    }

    /**
     * @param OptionalExpressionNode $astNode
     * @return bool
     */
    public function postVisitOptionalExpressionNode(OptionalExpressionNode $astNode)
    {
        $parents = $this->collaborators->parentNodeIds();
        $parents->pop();

        return true;
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
