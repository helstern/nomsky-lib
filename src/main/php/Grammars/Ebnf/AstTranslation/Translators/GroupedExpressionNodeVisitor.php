<?php namespace Helstern\Nomsky\Grammars\Ebnf\AstTranslation\Translators;

use Helstern\Nomsky\Grammar\Expressions\Group;
use Helstern\Nomsky\Grammars\Ebnf\Ast\GroupedExpressionNode;
use Helstern\Nomsky\Grammars\Ebnf\AstTranslation\VisitContext;

class GroupedExpressionNodeVisitor
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
     * @param GroupedExpressionNode $astNode
     * @return bool
     */
    public function preVisitGroupedExpressionNode(GroupedExpressionNode $astNode)
    {
        return true;
    }

    /**
     * @param GroupedExpressionNode $astNode
     * @return bool
     */
    public function visitGroupedExpressionNode(GroupedExpressionNode $astNode)
    {
    }

    /**
     * @param GroupedExpressionNode $astNode
     * @return bool
     */
    public function postVisitGroupedExpressionNode(GroupedExpressionNode $astNode)
    {
        $child = $this->visitContext->popOneExpression();
        $expression = new Group($child);
        $this->visitContext->pushExpression($expression);
    }
}
