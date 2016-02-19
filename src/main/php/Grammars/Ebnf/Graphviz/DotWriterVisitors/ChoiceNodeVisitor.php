<?php namespace Helstern\Nomsky\Grammars\Ebnf\Graphviz\DotWriterVisitors;

use Helstern\Nomsky\Grammars\Ebnf\Ast\ChoiceNode;
use Helstern\Nomsky\Grammars\Ebnf\Graphviz\Formatter;
use Helstern\Nomsky\Grammars\Ebnf\Graphviz\VisitContext;
use Helstern\Nomsky\Graphviz\DotWriter;

class ChoiceNodeVisitor extends AbstractVisitor
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
     * @param ChoiceNode $astNode
     *
     * @return bool
     */
    public function preVisitChoiceNode(ChoiceNode $astNode)
    {
        $this->visitContext->incrementNodeCount($astNode);
        return true;
    }

    /**
     * @param ChoiceNode $astNode
     *
     * @return bool
     */
    public function visitChoiceNode(ChoiceNode $astNode)
    {
        $increment = $this->visitContext->countParentIds();
        $this->formatter->indent($increment, $this->dotWriter);

        $parentId = $this->visitContext->peekParentId();
        $nodeId    = $this->buildNumberedDOTIdentifier('"alternative[%s]"', $this->visitContext);
        $this->dotWriter->writeEdgeStatement($parentId, $nodeId);

        $this->formatter->whitespace(1, $this->dotWriter); //formatting options
        $this->dotWriter->writeStatementTerminator();

        if (0 < $astNode->countChildren()) {
            $this->visitContext->pushParentId($nodeId);
        }

        return true;
    }

    /**
     * @param ChoiceNode $astNode
     *
     * @return bool
     */
    public function postVisitChoiceNode(ChoiceNode $astNode)
    {
        if (0 < $astNode->countChildren()) {
            $this->visitContext->popParentId();
        }

        return true;
    }
}
