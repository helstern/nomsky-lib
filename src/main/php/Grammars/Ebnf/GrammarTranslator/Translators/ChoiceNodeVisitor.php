<?php namespace Helstern\Nomsky\Grammars\Ebnf\GrammarTranslator\Translators;

use Helstern\Nomsky\Grammar\Expressions\Choice;
use Helstern\Nomsky\Grammars\Ebnf\Ast\ChoiceNode;
use Helstern\Nomsky\Grammars\Ebnf\GrammarTranslator\VisitContext;

class ChoiceNodeVisitor
{
    /**
     * @var VisitContext
     */
    private $visitContext;

    /**
     * @param VisitContext $visitContext
     *
     */
    public function __construct(VisitContext $visitContext)
    {
        $this->visitContext = $visitContext;
    }

    /**
     * @param ChoiceNode $astNode
     *
     *@return bool
     */
    public function preVisitChoiceNode(ChoiceNode $astNode)
    {
        $this->visitContext->pushExpressionMarker($this);
        return true;
    }

    /**
     * @param ChoiceNode $astNode
     *
     * @return bool
     */
    public function visitChoiceNode(ChoiceNode $astNode)
    {
        return true;
    }

    /**
     * @param ChoiceNode $astNode
     *
     * @return bool
     */
    public function postVisitChoiceNode(ChoiceNode $astNode)
    {
        $children = $this->visitContext->popExpressions($this);
        $expression = new Choice(array_shift($children), $children);
        $this->visitContext->pushExpression($expression);
    }
}
