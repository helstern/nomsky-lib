<?php namespace Helstern\Nomsky\Grammar\Production;

use Helstern\Nomsky\Grammar\Expressions\Expression;
use Helstern\Nomsky\Grammar\Symbol\Symbol;
use Helstern\Nomsky\Grammar\Symbol\Predicate\SymbolPredicate;

interface Production extends \Countable
{
    /**
     * @return Expression
     */
    public function getExpression();

    /**
     * @return Symbol
     */
    public function getNonTerminal();

    /**
     * @param $nonTerminal
     * @return boolean
     */
    public function startsWith($nonTerminal);

    /**
     * @param \Helstern\Nomsky\Grammar\Symbol\Predicate\SymbolPredicate $predicate
     * @param int $max
     * @return int
     */
    public function countMax(SymbolPredicate $predicate, $max);

    /**
     * @param SymbolPredicate $predicate
     * @return int
     */
    public function countAll(SymbolPredicate $predicate);

    /**
     * @param \Helstern\Nomsky\Grammar\Symbol\Predicate\SymbolPredicate $predicate
     * @param int $max
     * @return Symbol[]
     */
    public function findMax(SymbolPredicate $predicate, $max);

    /**
     * @param \Helstern\Nomsky\Grammar\Symbol\Predicate\SymbolPredicate $predicate
     * @return Symbol|null
     */
    public function findFirst(SymbolPredicate $predicate);

    /**
     * @param \Helstern\Nomsky\Grammar\Symbol\Predicate\SymbolPredicate $predicate
     * @return Symbol[]
     */
    public function findAll(SymbolPredicate $predicate);

    /**
     * @return Symbol[]
     */
    public function findAllTerminals();

    /**
     * @return Symbol[]
     */
    public function findAllNonTerminals();

    /**
     * @return Symbol
     */
    public function getFirstSymbol();

    /**
     * @return Symbol[]
     */
    public function getSymbols();
}
