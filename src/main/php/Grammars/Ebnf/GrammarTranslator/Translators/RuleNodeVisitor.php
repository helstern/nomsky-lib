<?php namespace Helstern\Nomsky\Grammars\Ebnf\GrammarTranslator\Translators;


use Helstern\Nomsky\Grammar\Production\StandardProduction;
use Helstern\Nomsky\Grammar\Symbol\GenericSymbol;
use Helstern\Nomsky\Grammar\Symbol\Symbol;
use Helstern\Nomsky\Grammars\Ebnf\Ast\RuleNode;
use Helstern\Nomsky\Grammars\Ebnf\GrammarTranslator\VisitContext;

class RuleNodeVisitor
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
        $symbol = $this->visitContext->popLeftHandSymbol($this);
        $expressions = $this->visitContext->popExpressions($this);
        array_pop($expressions);

        $production = new StandardProduction($symbol, array_pop($expressions));

        $this->visitContext->collectProduction($production);
        return true;
    }
}
