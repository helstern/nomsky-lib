<?php namespace Helstern\Nomsky\Grammar\Symbol\Predicate;

use Helstern\Nomsky\Grammar\Symbol\Symbol;

class MatchCountingInterceptor implements SymbolPredicate, Interceptor
{
    /** @var SymbolPredicate */
    protected $predicate;

    /** @var int */
    protected $matchCount;

    /**
     * @param SymbolPredicate $symbolPredicate
     */
    public function __construct(SymbolPredicate $symbolPredicate)
    {
        $this->predicate = $symbolPredicate;
        $this->matchCount = 0;
    }

    /**
     * @return SymbolPredicate
     */
    public function getPredicate()
    {
        return $this->predicate;
    }

    /**
     * @return int
     */
    public function getMatchCount()
    {
        return $this->matchCount;
    }

    public function matchSymbol(Symbol $symbol)
    {
        $matches = $this->predicate->matchSymbol($symbol);
        if ($matches) {
            $this->matchCount++;
        }

        return $matches;
    }
}
