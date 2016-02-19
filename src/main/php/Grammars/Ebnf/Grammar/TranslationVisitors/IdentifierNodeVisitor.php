<?php namespace Helstern\Nomsky\Grammars\Ebnf\Grammar\TranslationVisitors;

use Helstern\Nomsky\Grammar\Expressions\ExpressionSymbol;
use Helstern\Nomsky\Grammar\Symbol\Symbol;
use Helstern\Nomsky\Grammars\Ebnf\Ast\IdentifierNode;
use Helstern\Nomsky\Grammars\Ebnf\Grammar\AstTranslatorContext;

class IdentifierNodeVisitor
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
     * @throws \DomainException
     */
    public function visitIdentifierNode(IdentifierNode $astNode)
    {
        $name = $astNode->getIdentifierName();

        $uppercase = strtoupper($name);
        if ($uppercase == $name) {
            $expression = new ExpressionSymbol(Symbol::TYPE_TERMINAL, $name);
            $this->visitContext->pushExpression($expression);
            return true;
        }

        $lowercase = strtolower($name);
        if ($lowercase == $name) {
            $expression = new ExpressionSymbol(Symbol::TYPE_NON_TERMINAL, $name);
            $this->visitContext->pushExpression($expression);
            return true;
        }

        throw new \DomainException('unknown type of identifier name');
    }

    /**
     * @param IdentifierNode $astNode
     * @return bool
     */
    public function postVisitIdentifierNode(IdentifierNode $astNode)
    {
        return true;
    }
}
