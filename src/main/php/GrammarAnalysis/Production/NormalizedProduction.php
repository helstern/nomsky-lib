<?php namespace Helstern\Nomsky\GrammarAnalysis\Production;

use Helstern\Nomsky\Grammar\Expressions\ExpressionSymbol;
use Helstern\Nomsky\Grammar\Symbol\Predicate\SymbolPredicate;
use Helstern\Nomsky\Grammar\Symbol\Predicate\SymbolTypeEquals;
use Helstern\Nomsky\Grammar\Symbol\Symbol;

class NormalizedProduction
{
    /** @var Symbol */
    private $lhs;

    /** @var array|ExpressionSymbol[] */
    private $rhs;

    /**
     * @param \Helstern\Nomsky\Grammar\Symbol\Symbol $lhs
     * @param array|ExpressionSymbol[] $rhs
     */
    public function __construct(Symbol $lhs, array $rhs)
    {
        $this->lhs = $lhs;
        $this->rhs = $rhs;
    }

    /**
     * @return Symbol
     */
    public function getLeftHandSide()
    {
        return $this->lhs;
    }

    /**
     * @return Symbol[]
     */
    public function getRightHandSide()
    {
        return $this->rhs;
    }

    /**
     * @param $nonTerminal
     *
     * @return boolean
     */
    public function startsWith($nonTerminal)
    {
        // TODO: Implement startsWith() method.
    }

    /**
     * @param \Helstern\Nomsky\Grammar\Symbol\Predicate\SymbolPredicate $predicate
     * @param int $max
     *
     * @return int
     */
    public function countMax(SymbolPredicate $predicate, $max)
    {
        $matchedCount = 0;

        if ($max <= $matchedCount) {
            return $matchedCount;
        }

        foreach ($this->rhs as $symbol) {
            if ($predicate->matchSymbol($symbol)) {
                $matchedCount++;
                if ($matchedCount == $max) {
                    break;
                }
            }
        }

        return $matchedCount;
    }

    /**
     * @param SymbolPredicate $predicate
     *
     * @return int
     */
    public function countAll(SymbolPredicate $predicate)
    {
        $matchedCount = 0;

        foreach ($this->rhs as $symbol) {
            if ($predicate->matchSymbol($symbol)) {
                $matchedCount++;
            }
        }

        return $matchedCount;
    }

    /**
     * @param \Helstern\Nomsky\Grammar\Symbol\Predicate\SymbolPredicate $predicate
     * @param int $max
     *
     * @return Symbol[]
     */
    public function findMax(SymbolPredicate $predicate, $max)
    {
        $matched = [];
        $matchedCount = 0;

        if ($max <= $matchedCount) {
            return $matched;
        }

        foreach ($this->rhs as $symbol) {
            if ($predicate->matchSymbol($symbol)) {
                $matched[] = $symbol;
                $matchedCount++;
                if ($matchedCount == $max) {
                    break;
                }
            }
        }

        return $matched;
    }

    /**
     * @param \Helstern\Nomsky\Grammar\Symbol\Predicate\SymbolPredicate $predicate
     *
     * @return Symbol|null
     */
    public function findFirst(SymbolPredicate $predicate)
    {
        $found = null;
        foreach ($this->rhs as $symbol) {
            if ($predicate->matchSymbol($symbol)) {
                $found = $symbol;
            }
        }

        return $found;
    }

    public function findAll(SymbolPredicate $predicate)
    {
        return $this->findMax($predicate, PHP_INT_MAX);
    }

    public function findAllTerminals()
    {
        $predicate = SymbolTypeEquals::newInstance(Symbol::TYPE_TERMINAL);
        return $this->findMax($predicate, PHP_INT_MAX);
    }

    public function findAllNonTerminals()
    {
        $predicate = SymbolTypeEquals::newInstance(Symbol::TYPE_NON_TERMINAL);
        return $this->findMax($predicate, PHP_INT_MAX);
    }

    /**
     * @return Symbol
     */
    public function getFirstSymbol()
    {
        return $this->rhs[0];
    }

    public function count()
    {
        return count($this->rhs);
    }
}
