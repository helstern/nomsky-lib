<?php namespace Helstern\Nomsky\Grammars\Ebnf\GrammarTranslation\Translators;

use Helstern\Nomsky\Grammar\Expressions\OptionalList;
use Helstern\Nomsky\Grammar\Expressions\Repetition;
use Helstern\Nomsky\Grammars\Ebnf\Ast\OptionalExpressionNode;
use Helstern\Nomsky\Grammars\Ebnf\Ast\RepeatedExpressionNode;
use Helstern\Nomsky\Grammars\Ebnf\GrammarTranslation\VisitContext;

class RepetitionVisitor
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
     * @param RepeatedExpressionNode $astNode
     * @return bool
     */
    public function preVisitRepeatedExpressionNode(RepeatedExpressionNode $astNode)
    {
        $this->visitContext->pushExpressionMarker($this);
        return true;
    }

    /**
     * @param RepeatedExpressionNode $astNode
     * @return bool
     */
    public function visitRepeatedExpressionNode(RepeatedExpressionNode $astNode)
    {
        return true;
    }

    /**
     * @param RepeatedExpressionNode $astNode
     * @return bool
     */
    public function postVisitRepeatedExpressionNode(RepeatedExpressionNode $astNode)
    {
        $child = $this->visitContext->popOneExpression($this);
        $expression = new Repetition($child);
        $this->visitContext->pushExpression($expression);

        return true;
    }
}
