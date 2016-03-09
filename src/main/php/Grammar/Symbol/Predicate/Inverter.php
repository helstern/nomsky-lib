<?php namespace Helstern\Nomsky\Grammar\Symbol\Predicate;

use Helstern\Nomsky\Grammar\Symbol\Symbol;

class Inverter implements SymbolPredicate
{
    /** @var SymbolPredicate */
    protected $predicate;

    /**
     * @param SymbolPredicate $predicate
     *
*@return Inverter
     */
    static public function newInstance(SymbolPredicate $predicate)
    {
        return new self($predicate);
    }

    /**
     * @param SymbolPredicate $predicate
     */
    public function __construct(SymbolPredicate $predicate)
    {
        $this->predicate = $predicate;
    }

    /**
     * @return SymbolPredicate
     */
    public function getPredicate()
    {
        return $this->predicate;
    }

    public function matchSymbol(Symbol $symbol)
    {
        return ! $this->predicate->matchSymbol($symbol);
    }
}
