<?php namespace Helstern\Nomsky\Grammars\Ebnf\AstTranslation\Translators;

use Helstern\Nomsky\Grammar\Expressions\OptionalList;
use Helstern\Nomsky\Grammars\Ebnf\Ast\OptionalExpressionNode;
use Helstern\Nomsky\Grammars\Ebnf\AstTranslation\VisitContext;

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
        $child = $this->visitContext->popOneExpression();
        $expression = new OptionalList($child);
        $this->visitContext->pushExpression($expression);

        return true;
    }
}
