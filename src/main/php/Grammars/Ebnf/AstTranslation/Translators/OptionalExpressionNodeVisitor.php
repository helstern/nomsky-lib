<?php namespace Helstern\Nomsky\Grammars\Ebnf\AstTranslation\Translators;

use Helstern\Nomsky\Grammar\Expressions\OptionalList;
use Helstern\Nomsky\Grammars\Ebnf\Ast\OptionalExpressionNode;
use Helstern\Nomsky\Grammars\Ebnf\AstTranslation\VisitContext;
use Helstern\Nomsky\Parser\Ast\AstNodeVisitor;
use Helstern\Nomsky\Parser\AstNodeVisitor\AbstractDispatchingVisitor;
use Helstern\Nomsky\Parser\AstNodeVisitor\VisitDispatcher;

class OptionalExpressionNodeVisitor extends AbstractDispatchingVisitor implements AstNodeVisitor
{
    /**
     * @var VisitContext
     */
    private $visitContext;

    /** @var VisitDispatcher  */
    protected $visitDispatcher;

    /**
     * @param VisitContext $visitContext
     * @param VisitDispatcher $visitDispatcher
     *
     */
    public function __construct(VisitContext $visitContext, VisitDispatcher $visitDispatcher)
    {
        $this->visitContext = $visitContext;
        $this->visitDispatcher = $visitDispatcher;
    }

    /**
     * @return VisitDispatcher
     */
    protected function getVisitDispatcher()
    {
        $visitDispatcher = $this->visitDispatcher;
        return $visitDispatcher;
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
