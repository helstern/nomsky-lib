<?php namespace Helstern\Nomsky\Grammars\Ebnf\AstTranslation\Translators;

use Helstern\Nomsky\Grammar\Expressions\Group;
use Helstern\Nomsky\Grammars\Ebnf\Ast\GroupedExpressionNode;
use Helstern\Nomsky\Grammars\Ebnf\AstTranslation\VisitContext;
use Helstern\Nomsky\Parser\Ast\AstNodeVisitor;
use Helstern\Nomsky\Parser\AstNodeVisitor\AbstractDispatchingVisitor;
use Helstern\Nomsky\Parser\AstNodeVisitor\VisitDispatcher;

class GroupedExpressionNodeVisitor extends AbstractDispatchingVisitor implements AstNodeVisitor
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
