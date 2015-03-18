<?php namespace Helstern\Nomsky\Grammars\Ebnf\Graphviz\DotWriterVisitors;

use Helstern\Nomsky\Grammars\Ebnf\Ast\CommentNode;
use Helstern\Nomsky\Grammars\Ebnf\Graphviz\VisitorCollaborators;
use Helstern\Nomsky\Parser\Ast\AstNodeVisitor;
use Helstern\Nomsky\Parser\AstNodeVisitor\AbstractDispatchingVisitor;
use Helstern\Nomsky\Parser\AstNodeVisitor\VisitDispatcher;

class CommentNodeVisitor extends AbstractDispatchingVisitor implements AstNodeVisitor
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
     * @param CommentNode $astNode
     * @return string
     */
    protected function buildDOTNodeId(CommentNode $astNode)
    {
        $nodeCounter = $this->collaborators->nodeCounter();
        $idNumber = $nodeCounter->getNodeCount();

        return '"' . 'comment' . '[' .$idNumber .  ']' . '"';
    }

    public function preVisitCommentNode(CommentNode $astNode)
    {
        $nodeCounter = $this->collaborators->nodeCounter();
        $nodeCounter->increment($astNode);
    }

    public function visitCommentNode(CommentNode $astNode)
    {
        $dotWriter = $this->collaborators->dotWriter();

        $nodeId    = $this->buildDOTNodeId($astNode);
        $parents = $this->collaborators->parentNodeIds();
        $parentId = $parents->top();

        $dotWriter->writeEdgeStatement($parentId, $nodeId);
        $dotWriter->writeStatementTerminator();
    }

    public function postVisitCommentNode(CommentNode $astNode)
    {

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
