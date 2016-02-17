<?php namespace Helstern\Nomsky\Grammars\Ebnf\GrammarTranslator\Translators;

use Helstern\Nomsky\Grammar\Expressions\Group;
use Helstern\Nomsky\Grammars\Ebnf\Ast\GroupNode;
use Helstern\Nomsky\Grammars\Ebnf\GrammarTranslator\VisitContext;

class GroupNodeVisitor
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
     * @param GroupNode $astNode
     *
     * @return bool
     */
    public function preVisitGroupNode(GroupNode $astNode)
    {
        $this->visitContext->pushExpressionMarker($this);
        return true;
    }

    /**
     * @param GroupNode $astNode
     *
     * @return bool
     */
    public function visitGroupNode(GroupNode $astNode)
    {}

    /**
     * @param GroupNode $astNode
     *
     * @return bool
     */
    public function postVisitGroupNode(GroupNode $astNode)
    {
        $child = $this->visitContext->popOneExpression($this);
        $expression = new Group($child);
        $this->visitContext->pushExpression($expression);
    }
}
