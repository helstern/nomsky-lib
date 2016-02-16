<?php namespace Helstern\Nomsky\Grammars\Ebnf\GrammarTranslation\Translators;

use Helstern\Nomsky\Grammar\Expressions\ExpressionSymbol;
use Helstern\Nomsky\Grammar\Symbol\Symbol;
use Helstern\Nomsky\Grammars\Ebnf\Ast\StringLiteralNode;
use Helstern\Nomsky\Grammars\Ebnf\GrammarTranslation\VisitContext;

class StringLiteralNodeVisitor
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
