<?php namespace Helstern\Nomsky\Grammars\Ebnf\Graphviz\DotWriterVisitors;

use Helstern\Nomsky\Grammars\Ebnf\Ast\SequenceNode;
use Helstern\Nomsky\Grammars\Ebnf\Graphviz\Formatter;
use Helstern\Nomsky\Grammars\Ebnf\Graphviz\VisitContext;
use Helstern\Nomsky\Graphviz\DotWriter;

class SequenceNodeVisitor extends AbstractVisitor
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
     * @param SequenceNode $astNode
     * @return bool
     */
    public function preVisitSequenceNode(SequenceNode $astNode)
    {
        $this->visitContext->incrementNodeCount($astNode);
        return true;
    }

    /**
     * @param SequenceNode $astNode
     * @return bool
     */
    public function visitSequenceNode(SequenceNode $astNode)
    {
        $increment = $this->visitContext->countParentIds();
        $this->formatter->indent($increment, $this->dotWriter);

        $nodeId    = $this->buildNumberedDOTIdentifier('"sequence[%s]"', $this->visitContext);
        $parentId = $this->visitContext->peekParentId();

        $this->dotWriter->writeEdgeStatement($parentId, $nodeId);
        $this->formatter->whitespace(1, $this->dotWriter); //formatting options
        $this->dotWriter->writeStatementTerminator();

        if (0 < $astNode->countChildren()) {
            $this->visitContext->pushParentId($nodeId);
        }

        return true;
    }

    /**
     * @param SequenceNode $astNode
     * @return bool
     */
    public function postVisitSequenceNode(SequenceNode $astNode)
    {
        if (0 < $astNode->countChildren()) {
            $this->visitContext->popParentId();
        }

        return true;
    }
}
