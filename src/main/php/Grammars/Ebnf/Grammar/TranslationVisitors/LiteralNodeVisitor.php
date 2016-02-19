<?php namespace Helstern\Nomsky\Grammars\Ebnf\Grammar\TranslationVisitors;

use Helstern\Nomsky\Grammar\Expressions\ExpressionSymbol;
use Helstern\Nomsky\Grammar\Symbol\Symbol;
use Helstern\Nomsky\Grammars\Ebnf\Ast\LiteralNode;
use Helstern\Nomsky\Grammars\Ebnf\Grammar\AstTranslatorContext;

class LiteralNodeVisitor
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
