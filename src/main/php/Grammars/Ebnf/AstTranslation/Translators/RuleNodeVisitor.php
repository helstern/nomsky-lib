<?php namespace Helstern\Nomsky\Grammars\Ebnf\AstTranslation\Translators;


use Helstern\Nomsky\Grammar\Production\DefaultProduction;
use Helstern\Nomsky\Grammar\Symbol\GenericSymbol;
use Helstern\Nomsky\Grammar\Symbol\Symbol;
use Helstern\Nomsky\Grammars\Ebnf\Ast\RuleNode;
use Helstern\Nomsky\Grammars\Ebnf\AstTranslation\VisitContext;

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

        $this->visitContext->pushSymbol($symbol, $this);

        return true;
    }

    /**
     * @param RuleNode $astNode
     * @return bool
     */
    public function postVisitRuleNode(RuleNode $astNode)
    {
        $symbol = $this->visitContext->popSymbol($this);
        $expression = $this->visitContext->popOneExpression($this);
        $production = new DefaultProduction($symbol, $expression);

        $this->visitContext->addProduction($production);
        return true;
    }
}
