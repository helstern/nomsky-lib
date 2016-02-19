<?php namespace Helstern\Nomsky\Grammars\Ebnf\Grammar\TranslationVisitors;


use Helstern\Nomsky\Grammar\Production\StandardProduction;
use Helstern\Nomsky\Grammar\Symbol\GenericSymbol;
use Helstern\Nomsky\Grammar\Symbol\Symbol;
use Helstern\Nomsky\Grammars\Ebnf\Ast\RuleNode;
use Helstern\Nomsky\Grammars\Ebnf\Grammar\AstTranslatorContext;

class RuleNodeVisitor
{
    /**
     * @var AstTranslatorContext
     */
    private $visitContext;

    /**
     * @param AstTranslatorContext $visitContext
     */
    public function __construct(AstTranslatorContext $visitContext)
    {
        $this->visitContext = $visitContext;
    }

    /**
     * @param RuleNode $astNode
     * @return bool
     */
    public function preVisitRuleNode(RuleNode $astNode)
    {
        $this->visitContext->pushExpressionMarker($this);
        return true;
    }

    /**
     * @param RuleNode $astNode
     * @return bool
     */
    public function visitRuleNode(RuleNode $astNode)
    {
        $identifierNode = $astNode->getIdentifierNode();
        $name = $identifierNode->getIdentifierName();
        $symbol = new GenericSymbol(Symbol::TYPE_NON_TERMINAL, $name);

        $this->visitContext->pushLeftHandSymbol($symbol, $this);

        return true;
    }

    /**
     * @param RuleNode $astNode
     * @return bool
     */
    public function postVisitRuleNode(RuleNode $astNode)
    {
        $expressions = $this->visitContext->popExpressions($this);
        $symbol = $this->visitContext->popLeftHandSymbol($this);
        //remove the lhs we just popped from the visit context
        array_shift($expressions);
        $production = new StandardProduction($symbol, array_shift($expressions));

        $this->visitContext->collectProduction($production);
        return true;
    }
}
