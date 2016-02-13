<?php namespace Helstern\Nomsky\Grammars\Ebnf\AstTranslation\Translators;

use Helstern\Nomsky\Grammar\Expressions\ExpressionSymbol;
use Helstern\Nomsky\Grammar\Symbol\Symbol;
use Helstern\Nomsky\Grammars\Ebnf\Ast\StringLiteralNode;
use Helstern\Nomsky\Grammars\Ebnf\AstTranslation\VisitContext;
use Helstern\Nomsky\Parser\Ast\AstNodeVisitor;
use Helstern\Nomsky\Parser\AstNodeVisitor\AbstractDispatchingVisitor;
use Helstern\Nomsky\Parser\AstNodeVisitor\VisitDispatcher;

class StringLiteralNodeVisitor extends AbstractDispatchingVisitor implements AstNodeVisitor
{
    /**
     * @var VisitContext
     */
    private $visitContext;

    /**
     * @var VisitDispatcher
     */
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
     * @param StringLiteralNode $astNode
     * @return bool
     */
    public function preVisitStringLiteralNode(StringLiteralNode $astNode)
    {
        return true;
    }

    /**
     * @param StringLiteralNode $astNode
     * @return bool
     */
    public function visitStringLiteralNode(StringLiteralNode $astNode)
    {
        $expression = new ExpressionSymbol(Symbol::TYPE_TERMINAL, $astNode->getLiteral());
        $this->visitContext->pushExpression($expression);

        return true;
    }

    /**
     * @param StringLiteralNode $astNode
     * @return bool
     */
    public function postVisitStringLiteralNode(StringLiteralNode $astNode)
    {
        return true;
    }
}
