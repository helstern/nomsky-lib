<?php namespace Helstern\Nomsky\Grammars\Ebnf\GrammarTranslator\Translators;

use Helstern\Nomsky\Grammar\Expressions\ExpressionSymbol;
use Helstern\Nomsky\Grammar\Symbol\Symbol;
use Helstern\Nomsky\Grammars\Ebnf\Ast\IdentifierNode;
use Helstern\Nomsky\Grammars\Ebnf\GrammarTranslator\VisitContext;

class IdentifierNodeVisitor
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
     * @param IdentifierNode $astNode
     * @return bool
     */
    public function preVisitIdentifierNode(IdentifierNode $astNode)
    {
        return true;
    }

    /**
     * @param IdentifierNode $astNode
     * @return bool
     */
    public function visitIdentifierNode(IdentifierNode $astNode)
    {
        return true;
    }

    /**
     * @param IdentifierNode $astNode
     * @return bool
     */
    public function postVisitIdentifierNode(IdentifierNode $astNode)
    {
        $name = $astNode->getIdentifierName();
        $expression = new ExpressionSymbol(Symbol::TYPE_TERMINAL, $name);
        $this->visitContext->pushExpression($expression);
    }
}
