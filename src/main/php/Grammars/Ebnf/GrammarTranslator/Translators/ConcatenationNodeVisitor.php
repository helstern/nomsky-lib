<?php namespace Helstern\Nomsky\Grammars\Ebnf\GrammarTranslator\Translators;

use Helstern\Nomsky\Grammar\Expressions\Concatenation;
use Helstern\Nomsky\Grammars\Ebnf\Ast\ConcatenationNode;
use Helstern\Nomsky\Grammars\Ebnf\GrammarTranslator\VisitContext;

class ConcatenationNodeVisitor
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
     * @param ConcatenationNode $astNode
     *
     * @return bool
     */
    public function preVisitConcatenationNode(ConcatenationNode $astNode)
    {
        $this->visitContext->pushExpressionMarker($this);
        return true;
    }

    /**
     * @param ConcatenationNode $astNode
     *
     * @return bool
     */
    public function visitConcatenationNode(ConcatenationNode $astNode)
    {
        return true;
    }

    /**
     * @param ConcatenationNode $astNode
     *
     * @return bool
     */
    public function postVisitConcatenationNode(ConcatenationNode $astNode)
    {
        $children = $this->visitContext->popExpressions($this);
        $expression = new Concatenation(array_shift($children), $children);
        $this->visitContext->pushExpression($expression);
    }

}
