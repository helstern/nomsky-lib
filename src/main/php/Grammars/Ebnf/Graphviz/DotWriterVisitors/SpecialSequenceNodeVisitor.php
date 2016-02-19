<?php namespace Helstern\Nomsky\Grammars\Ebnf\Graphviz\DotWriterVisitors;

use Helstern\Nomsky\Grammars\Ebnf\Ast\SpecialSequenceNode;
use Helstern\Nomsky\Grammars\Ebnf\Graphviz\Formatter;
use Helstern\Nomsky\Grammars\Ebnf\Graphviz\VisitContext;
use Helstern\Nomsky\Graphviz\DotWriter;

class SpecialSequenceNodeVisitor extends AbstractVisitor
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
     * @param SpecialSequenceNode $astNode
     * @return bool
     */
    public function preVisitSpecialSequenceNode(SpecialSequenceNode $astNode)
    {
        $this->visitContext->incrementNodeCount($astNode);
        return true;
    }

    /**
     * @param SpecialSequenceNode $astNode
     * @return bool
     */
    public function visitSpecialSequenceNode(SpecialSequenceNode $astNode)
    {
        $increment = $this->visitContext->countParentIds();
        $this->formatter->indent($increment, $this->dotWriter);

        $nodeId    = $this->buildNumberedDOTIdentifier('"special_sequence[%s]"', $this->visitContext);
        $parentId = $this->visitContext->peekParentId();

        $this->dotWriter->writeEdgeStatement($parentId, $nodeId);
        $this->formatter->whitespace(1, $this->dotWriter); //formatting options
        $this->dotWriter->writeStatementTerminator();

        //$this->visitContext->pushParentId($nodeId);
        return true;
    }

    /**
     * @param SpecialSequenceNode $astNode
     * @return bool
     */
    public function postVisitSpecialSequenceNode(SpecialSequenceNode $astNode)
    {
        return true;
    }

}
