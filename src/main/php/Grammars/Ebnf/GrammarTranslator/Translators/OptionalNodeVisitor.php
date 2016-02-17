<?php namespace Helstern\Nomsky\Grammars\Ebnf\GrammarTranslator\Translators;

use Helstern\Nomsky\Grammar\Expressions\Repetition;
use Helstern\Nomsky\Grammars\Ebnf\Ast\OptionalNode;
use Helstern\Nomsky\Grammars\Ebnf\GrammarTranslator\VisitContext;

class OptionalNodeVisitor
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
     * @param OptionalNode $astNode
     *
     * @return bool
     */
    public function preVisitOptionalNode(OptionalNode $astNode)
    {
        $this->visitContext->pushExpressionMarker($this);
        return true;
    }

    /**
     * @param OptionalNode $astNode
     *
     * @return bool
     */
    public function visitOptionalNode(OptionalNode $astNode)
    {
        return true;
    }

    /**
     * @param OptionalNode $astNode
     *
     * @return bool
     */
    public function postVisitOptionalNode(OptionalNode $astNode)
    {
        $child = $this->visitContext->popOneExpression($this);
        $expression = new Repetition($child);
        $this->visitContext->pushExpression($expression);

        return true;
    }
}
