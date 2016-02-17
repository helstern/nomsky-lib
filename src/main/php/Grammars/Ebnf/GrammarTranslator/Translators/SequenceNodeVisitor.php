<?php namespace Helstern\Nomsky\Grammars\Ebnf\GrammarTranslator\Translators;

use Helstern\Nomsky\Grammar\Expressions\Concatenation;
use Helstern\Nomsky\Grammars\Ebnf\Ast\SequenceNode;
use Helstern\Nomsky\Grammars\Ebnf\GrammarTranslator\VisitContext;

class SequenceNodeVisitor
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
     * @param SequenceNode $astNode
     *
     * @return bool
     */
    public function preVisitSequenceNode(SequenceNode $astNode)
    {
        $this->visitContext->pushExpressionMarker($this);
        return true;
    }

    /**
     * @param SequenceNode $astNode
     *
     * @return bool
     */
    public function visitSequenceNode(SequenceNode $astNode)
    {
        return true;
    }

    /**
     * @param SequenceNode $astNode
     *
     * @return bool
     */
    public function postVisitSequenceNode(SequenceNode $astNode)
    {
        $children = $this->visitContext->popExpressions($this);
        $expression = new Concatenation(array_shift($children), $children);
        $this->visitContext->pushExpression($expression);
    }

}
