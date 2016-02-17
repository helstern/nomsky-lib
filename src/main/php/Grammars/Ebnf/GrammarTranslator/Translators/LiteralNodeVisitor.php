<?php namespace Helstern\Nomsky\Grammars\Ebnf\GrammarTranslator\Translators;

use Helstern\Nomsky\Grammar\Expressions\ExpressionSymbol;
use Helstern\Nomsky\Grammar\Symbol\Symbol;
use Helstern\Nomsky\Grammars\Ebnf\Ast\LiteralNode;
use Helstern\Nomsky\Grammars\Ebnf\GrammarTranslator\VisitContext;

class LiteralNodeVisitor
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
     * @param LiteralNode $astNode
     *
     * @return bool
     */
    public function preVisitLiteralNode(LiteralNode $astNode)
    {
        return true;
    }

    /**
     * @param LiteralNode $astNode
     *
     * @return bool
     */
    public function visitLiteralNode(LiteralNode $astNode)
    {
        $expression = new ExpressionSymbol(Symbol::TYPE_TERMINAL, $astNode->getLiteral());
        $this->visitContext->pushExpression($expression);

        return true;
    }

    /**
     * @param LiteralNode $astNode
     *
     * @return bool
     */
    public function postVisitLiteralNode(LiteralNode $astNode)
    {
        return true;
    }
}
