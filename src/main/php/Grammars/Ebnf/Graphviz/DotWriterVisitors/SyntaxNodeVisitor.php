<?php namespace Helstern\Nomsky\Grammars\Ebnf\Graphviz\DotWriterVisitors;

use Helstern\Nomsky\Grammars\Ebnf\Ast\SyntaxNode;
use Helstern\Nomsky\Grammars\Ebnf\Graphviz\VisitorCollaborators;
use Helstern\Nomsky\Parser\Ast\AstNodeVisitor;
use Helstern\Nomsky\Parser\AstNodeVisitor\AbstractDispatchingVisitor;
use Helstern\Nomsky\Parser\AstNodeVisitor\VisitDispatcher;

class SyntaxNodeVisitor extends AbstractDispatchingVisitor implements AstNodeVisitor
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
     * @param SyntaxNode $astNode
     * @return string
     */
    protected function buildDOTNodeId(SyntaxNode $astNode)
    {
        return '"' . 'syntax' . '"';
    }

    public function preVisitSyntaxNode(SyntaxNode $astNode)
    {
        $dotWriter = $this->collaborators->dotWriter();
        $dotWriter->startDigraph();

        $nodeCounter = $this->collaborators->nodeCounter();
        $nodeCounter->increment($astNode);
    }

    public function visitSyntaxNode(SyntaxNode $astNode)
    {
        $dotWriter = $this->collaborators->dotWriter();

        $nodeId    = $this->buildDOTNodeId($astNode);
        $dotWriter->writeNode($nodeId);

        $dotWriter->writeStatementTerminator();

        if (0 < $astNode->countChildren()) {
            $parents = $this->collaborators->parentNodeIds();
            $parents->push($nodeId);
        }
    }

    public function postVisitSyntaxNode(SyntaxNode $astNode)
    {
        $dotWriter = $this->collaborators->dotWriter();
        $dotWriter->closeGraph();

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
