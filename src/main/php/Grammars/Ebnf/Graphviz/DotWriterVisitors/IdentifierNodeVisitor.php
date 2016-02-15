<?php namespace Helstern\Nomsky\Grammars\Ebnf\Graphviz\DotWriterVisitors;

use Helstern\Nomsky\Grammars\Ebnf\Ast\IdentifierNode;
use Helstern\Nomsky\Grammars\Ebnf\Graphviz\Formatter;
use Helstern\Nomsky\Grammars\Ebnf\Graphviz\VisitContext;
use Helstern\Nomsky\Graphviz\DotWriter;

class IdentifierNodeVisitor
{
    /**
     * @var VisitContext
     */
    private $visitContext;

    /**
     * @var DotWriter
     */
    private $dotWriter;

    /**
     * @var Formatter
     */
    private $formatter;

    /**
     * @param VisitContext $visitContext
     * @param DotWriter $dotWriter
     * @param Formatter $formatter
     */
    public function __construct(VisitContext $visitContext, DotWriter $dotWriter, Formatter $formatter)
    {
        $this->visitContext = $visitContext;
        $this->dotWriter = $dotWriter;
        $this->formatter = $formatter;
    }

    /**
     * @param IdentifierNode $astNode
     * @return string
     */
    protected function buildDOTIdentifier(IdentifierNode $astNode)
    {
        $idNumber = $this->visitContext->getNodeCount();
        $identifierName = $astNode->getIdentifierName();

        return sprintf('"%s[%s]"', $identifierName, $idNumber) ;
    }

    /**
     * @param IdentifierNode $astNode
     * @return bool
     */
    public function preVisitIdentifierNode(IdentifierNode $astNode)
    {
        $this->visitContext->incrementNodeCount($astNode);
        return true;
    }

    /**
     * @param IdentifierNode $astNode
     * @return bool
     */
    public function visitIdentifierNode(IdentifierNode $astNode)
    {
        $increment = $this->visitContext->countParentIds();
        $this->formatter->indent($increment, $this->dotWriter);

        $nodeId    = $this->buildDOTIdentifier($astNode);
        $parentId = $this->visitContext->peekParentId();

        $this->dotWriter->writeEdgeStatement($parentId, $nodeId);
        $this->formatter->whitespace(1, $this->dotWriter); //formatting options
        $this->dotWriter->writeStatementTerminator();

        return true;
    }

    /**
     * @param IdentifierNode $astNode
     * @return bool
     */
    public function postVisitIdentifierNode(IdentifierNode $astNode)
    {
        return true;
    }
}
