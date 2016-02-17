<?php namespace Helstern\Nomsky\Grammar;

use Helstern\Nomsky\Grammar\Expressions\Choice;
use Helstern\Nomsky\Grammar\Expressions\Expression;
use Helstern\Nomsky\Grammar\Expressions\Group;
use Helstern\Nomsky\Grammar\Expressions\Optional;
use Helstern\Nomsky\Grammar\Expressions\Repetition;
use Helstern\Nomsky\Grammar\Expressions\Concatenation;
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
     * @param Choice $expression
     *
*@return boolean
     */
    public function startVisitChoice(Choice $expression)
    {
        return true;
    }

    /**
     * @param Choice $expression
     *
*@return boolean
     */
    public function endVisitChoice(Choice $expression)
    {
        return true;
    }

    /**
     * @param Concatenation $expression
     *
*@return boolean
     */
    public function startVisitConcatenation(Concatenation $expression)
    {
        return true;
    }

    /**
     * @param Concatenation $expression
     *
*@return boolean
     */
    public function endVisitConcatenation(Concatenation $expression)
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
     * @param Repetition $expression
     *
*@return boolean
     */
    public function startVisitRepetition(Repetition $expression)
    {
        return true;
    }

    /**
     * @param Repetition $expression
     *
*@return boolean
     */
    public function endVisitRepetition(Repetition $expression)
    {
        return true;
    }

    /**
     * @param Optional $expression
     *
*@return boolean
     */
    public function startVisitOptional(Optional $expression)
    {
        return true;
    }

    /**
     * @param Optional $expression
     *
*@return boolean
     */
    public function endVisitOptional(Optional $expression)
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
