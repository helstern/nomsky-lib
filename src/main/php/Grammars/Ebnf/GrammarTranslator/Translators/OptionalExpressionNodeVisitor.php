<?php namespace Helstern\Nomsky\Grammars\Ebnf\GrammarTranslator\Translators;

use Helstern\Nomsky\Grammar\Expressions\Repetition;
use Helstern\Nomsky\Grammars\Ebnf\Ast\OptionalExpressionNode;
use Helstern\Nomsky\Grammars\Ebnf\GrammarTranslator\VisitContext;

class OptionalExpressionNodeVisitor
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
     * @param OptionalExpressionNode $astNode
     * @return bool
     */
    public function preVisitOptionalExpressionNode(OptionalExpressionNode $astNode)
    {
        $this->visitContext->pushExpressionMarker($this);
        return true;
    }

    /**
     * @param OptionalExpressionNode $astNode
     * @return bool
     */
    public function visitOptionalExpressionNode(OptionalExpressionNode $astNode)
    {
        return true;
    }

    /**
     * @param OptionalExpressionNode $astNode
     * @return bool
     */
    public function postVisitOptionalExpressionNode(OptionalExpressionNode $astNode)
    {
        $child = $this->visitContext->popOneExpression($this);
        $expression = new Repetition($child);
        $this->visitContext->pushExpression($expression);

        return true;
    }
}
