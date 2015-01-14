<?php namespace Helstern\Nomsky\Grammar;

use Helstern\Nomsky\Grammar\Expressions\Alternation;
use Helstern\Nomsky\Grammar\Expressions\Expression;
use Helstern\Nomsky\Grammar\Expressions\Group;
use Helstern\Nomsky\Grammar\Expressions\OptionalItem;
use Helstern\Nomsky\Grammar\Expressions\OptionalList;
use Helstern\Nomsky\Grammar\Expressions\Sequence;
use Helstern\Nomsky\Grammar\Expressions\ExpressionSymbol;
use Helstern\Nomsky\Grammar\Expressions\Visitor\HierarchyVisitor;
use Helstern\Nomsky\Grammar\Symbol\Comparator\HashCodeComparator;
use Helstern\Nomsky\Grammar\Symbol\Predicate\SymbolPredicate;
use Helstern\Nomsky\Grammar\Symbol\Symbol;

class SymbolCollectorVisitor implements HierarchyVisitor
{
    /** @var array Symbol[] */
    protected $terminals = array();

    /** @var array Symbol[] */
    protected $nonTerminals = array();

    /** @var SymbolPredicate */
    protected $symbolPredicate;

    /** @var array Symbol[] */
    protected $collectedSymbols;

    /**
     * @param SymbolPredicate $symbolPredicate
     */
    public function __construct(SymbolPredicate $symbolPredicate)
    {
        $this->symbolPredicate = $symbolPredicate;
    }

    /**
     * @param Alternation $expression
     * @return boolean
     */
    public function startVisitAlternation(Alternation $expression)
    {
        return true;
    }

    /**
     * @param Alternation $expression
     * @return boolean
     */
    public function endVisitAlternation(Alternation $expression)
    {
        return true;
    }

    /**
     * @param Sequence $expression
     * @return boolean
     */
    public function startVisitSequence(Sequence $expression)
    {
        return true;
    }

    /**
     * @param Sequence $expression
     * @return boolean
     */
    public function endVisitSequence(Sequence $expression)
    {
        return true;
    }

    /**
     * @param Group $expression
     * @return boolean
     */
    public function startVisitGroup(Group $expression)
    {
        return true;
    }

    /**
     * @param Group $expression
     * @return boolean
     */
    public function endVisitGroup(Group $expression)
    {
        return true;
    }

    /**
     * @param OptionalList $expression
     * @return boolean
     */
    public function startVisitOptionalList(OptionalList $expression)
    {
        return true;
    }

    /**
     * @param OptionalList $expression
     * @return boolean
     */
    public function endVisitOptionalList(OptionalList $expression)
    {
        return true;
    }

    /**
     * @param OptionalItem $expression
     * @return boolean
     */
    public function startVisitOptionalItem(OptionalItem $expression)
    {
        return true;
    }

    /**
     * @param OptionalItem $expression
     * @return boolean
     */
    public function endVisitOptionalItem(OptionalItem $expression)
    {
        return true;
    }

    /**
     * @param Expression $expression
     * @return boolean
     */
    public function visitExpression(Expression $expression)
    {
        if ($expression instanceof ExpressionSymbol || $expression instanceof Symbol) {
            $this->collectSymbol($expression);
        }

        return true;
    }

    /**
     * @return array|Symbol[]
     */
    public function getCollected()
    {
        if (count($this->collectedSymbols) == 0) {
            return array();
        }

        $unique = HashCodeComparator::singletonInstance()->unique($this->collectedSymbols);
        return $unique;
    }

    /**
     * @param Symbol $symbol
     * @return bool
     */
    protected function collectSymbol(Symbol $symbol)
    {
        if ($this->symbolPredicate->matchSymbol($symbol)) {
            $this->collectedSymbols[] = $symbol;
            return true;
        }

        return false;
    }
}
