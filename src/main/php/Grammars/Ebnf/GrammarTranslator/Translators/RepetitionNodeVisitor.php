<?php namespace Helstern\Nomsky\Grammars\Ebnf\GrammarTranslator\Translators;

use Helstern\Nomsky\Grammar\Expressions\Repetition;
use Helstern\Nomsky\Grammars\Ebnf\Ast\RepetitionNode;
use Helstern\Nomsky\Grammars\Ebnf\GrammarTranslator\VisitContext;

class RepetitionNodeVisitor
{
    /**
     * @var VisitContext
     */
    private $visitContext;

    /**
     * @param VisitContext $visitContext
     */
    public function __construct(VisitContext $visitContext)
    {
        $this->visitContext = $visitContext;
    }

    /**
     * @param RepetitionNode $astNode
     *
     * @return bool
     */
    public function preVisitRepetitionNode(RepetitionNode $astNode)
    {
        $this->visitContext->pushExpressionMarker($this);
        return true;
    }

    /**
     * @param RepetitionNode $astNode
     *
     * @return bool
     */
    public function visitRepetitionNode(RepetitionNode $astNode)
    {
        return true;
    }

    /**
     * @param RepetitionNode $astNode
     *
     * @return bool
     */
    public function postVisitRepetitionNode(RepetitionNode $astNode)
    {
        $child = $this->visitContext->popOneExpression($this);
        $expression = new Repetition($child);
        $this->visitContext->pushExpression($expression);

        return true;
    }
}
