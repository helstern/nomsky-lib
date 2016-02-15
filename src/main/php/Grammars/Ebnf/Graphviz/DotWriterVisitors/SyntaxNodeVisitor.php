<?php namespace Helstern\Nomsky\Grammars\Ebnf\Graphviz\DotWriterVisitors;

use Helstern\Nomsky\Grammars\Ebnf\Ast\SyntaxNode;
use Helstern\Nomsky\Grammars\Ebnf\Graphviz\Formatter;
use Helstern\Nomsky\Grammars\Ebnf\Graphviz\VisitContext;
use Helstern\Nomsky\Graphviz\DotWriter;

class SyntaxNodeVisitor
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
     * @param SyntaxNode $astNode
     * @return string
     */
    protected function buildDOTIdentifier(SyntaxNode $astNode)
    {
        return '"syntax"';
    }

    /**
     * @param SyntaxNode $astNode
     * @return bool
     */
    public function preVisitSyntaxNode(SyntaxNode $astNode)
    {
        $this->dotWriter->startGraph();
        $this->visitContext->incrementNodeCount($astNode);

        return true;
    }

    /**
     * @param SyntaxNode $astNode
     * @return bool
     */
    public function visitSyntaxNode(SyntaxNode $astNode)
    {
        $nodeId    = $this->buildDOTIdentifier($astNode);
        $this->dotWriter->writeNode($nodeId);

        $this->formatter->whitespace(1, $this->dotWriter); //formatting options
        $this->dotWriter->writeStatementTerminator();

        if (0 < $astNode->countChildren()) {
            $this->visitContext->pushParentId($nodeId);
        }

        return true;
    }

    /**
     * @param SyntaxNode $astNode
     * @return bool
     */
    public function postVisitSyntaxNode(SyntaxNode $astNode)
    {
        $this->dotWriter->closeGraph();
        $this->visitContext->popParentId();

        return true;
    }
}
