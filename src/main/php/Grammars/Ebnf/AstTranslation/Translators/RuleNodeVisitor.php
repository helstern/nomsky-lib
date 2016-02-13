<?php namespace Helstern\Nomsky\Grammars\Ebnf\AstTranslation\Translators;


use Helstern\Nomsky\Grammar\Production\DefaultProduction;
use Helstern\Nomsky\Grammar\Symbol\GenericSymbol;
use Helstern\Nomsky\Grammar\Symbol\Symbol;
use Helstern\Nomsky\Grammars\Ebnf\Ast\RuleNode;
use Helstern\Nomsky\Grammars\Ebnf\AstTranslation\VisitContext;
use Helstern\Nomsky\Parser\Ast\AstNodeVisitor;
use Helstern\Nomsky\Parser\AstNodeVisitor\AbstractDispatchingVisitor;
use Helstern\Nomsky\Parser\AstNodeVisitor\VisitDispatcher;

class RuleNodeVisitor extends AbstractDispatchingVisitor implements AstNodeVisitor
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
