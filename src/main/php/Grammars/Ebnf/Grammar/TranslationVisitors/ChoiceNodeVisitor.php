<?php namespace Helstern\Nomsky\Grammars\Ebnf\Grammar\TranslationVisitors;

use Helstern\Nomsky\Grammar\Expressions\Choice;
use Helstern\Nomsky\Grammars\Ebnf\Ast\ChoiceNode;
use Helstern\Nomsky\Grammars\Ebnf\Grammar\AstTranslatorContext;

class ChoiceNodeVisitor
{
    /**
     * @var AstTranslatorContext
     */
    private $visitContext;

    /**
     * @param AstTranslatorContext $visitContext
     *
     */
    public function __construct(AstTranslatorContext $visitContext)
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
