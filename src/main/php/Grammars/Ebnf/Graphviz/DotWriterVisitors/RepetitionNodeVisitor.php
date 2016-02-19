<?php namespace Helstern\Nomsky\Grammars\Ebnf\Graphviz\DotWriterVisitors;

use Helstern\Nomsky\Grammars\Ebnf\Ast\RepetitionNode;
use Helstern\Nomsky\Grammars\Ebnf\Graphviz\Formatter;
use Helstern\Nomsky\Grammars\Ebnf\Graphviz\VisitContext;
use Helstern\Nomsky\Graphviz\DotWriter;

class RepetitionNodeVisitor extends AbstractVisitor
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
     * @param RepetitionNode $astNode
     *
     * @return bool
     */
    public function preVisitRepetitionNode(RepetitionNode $astNode)
    {
        $this->visitContext->incrementNodeCount($astNode);
        return true;
    }

    /**
     * @param RepetitionNode $astNode
     *
     * @return bool
     */
    public function visitRepetitionNode(RepetitionNode $astNode)
    {
        $increment = $this->visitContext->countParentIds();
        $this->formatter->indent($increment, $this->dotWriter);

        $parentId = $this->visitContext->peekParentId();
        $nodeId    = $this->buildNumberedDOTIdentifier('"repeated_expression[%s]"', $this->visitContext);

        $this->dotWriter->writeEdgeStatement($parentId, $nodeId);
        $this->formatter->whitespace(1, $this->dotWriter); //formatting options
        $this->dotWriter->writeStatementTerminator();

        $this->visitContext->pushParentId($nodeId);

        return true;
    }

    /**
     * @param RepetitionNode $astNode
     *
     * @return bool
     */
    public function postVisitRepetitionNode(RepetitionNode $astNode)
    {
        $this->visitContext->popParentId();
        return true;
    }
}
