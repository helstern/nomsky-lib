<?php namespace Helstern\Nomsky\Grammar\Symbol\Predicate;

use Helstern\Nomsky\Grammar\Symbol\Symbol;

interface SymbolPredicate
{
    public function matchSymbol(Symbol $symbol);
}
