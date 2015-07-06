<?php namespace Helstern\Nomsky\Grammar\Symbol\Predicate;

use Helstern\Nomsky\Grammar\Symbol\Symbol;

class AndPredicate implements SymbolPredicate
{
    /** @var array|SymbolPredicate[] */
    protected $listOfPredicates;

    /**
     * @param array|SymbolPredicate[] $listOfPredicates
     */
    public function __construct(array $listOfPredicates)
    {
        $this->listOfPredicates = $listOfPredicates;
    }

    public function matchSymbol(Symbol $symbol)
    {
        foreach($this->listOfPredicates as $predicate) {
            if (!$predicate->matchSymbol($symbol)) {
                return false;
            }
        }

        return true;
    }
}
